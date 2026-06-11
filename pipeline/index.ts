import { Elysia } from "elysia";

const pythonCommands = [process.env.PYTHON_BIN ?? "python", "python3"];

function sanitizeBody(body: unknown): Record<string, unknown> {
    const allowedFields = ["text", "command"];
    const sanitizedBody: Record<string, unknown> = {};

    if (typeof body !== "object" || body === null) {
        return sanitizedBody;
    }

    for (const [key, value] of Object.entries(body)) {
        if (!allowedFields.includes(key)) {
            continue;
        }

        if (key === "text" && typeof value === "string") {
            sanitizedBody[key] = value.slice(0, 10000);
            continue;
        }

        if (key === "command" && typeof value === "string") {
            const validCommands = [
                "analyze",
                "seo_title",
                "seo_description",
                "summary",
                "description",
                "cta",
                "business_audit",
            ];
            sanitizedBody[key] = validCommands.includes(value)
                ? value
                : "analyze";
            continue;
        }

        sanitizedBody[key] = value;
    }

    return sanitizedBody;
}

function spawnPythonProcess() {
    for (const python of pythonCommands) {
        try {
            return Bun.spawn([python, "python/analyzer.py"], {
                stdin: "pipe",
                stdout: "pipe",
                stderr: "pipe",
            });
        } catch (error) {
            console.warn(
                `AI Pipeline: failed to spawn ${python}:`,
                error?.message ?? error,
            );
        }
    }

    throw new Error(
        "No Python executable found. Install Python or set PYTHON_BIN.",
    );
}

function jsonResponse(body: Record<string, unknown>, status = 200) {
    return new Response(JSON.stringify(body), {
        status,
        headers: {
            "content-type": "application/json",
        },
    });
}

const app = new Elysia()
    .post("/analyze", async ({ body }) => {
        try {
            const sanitizedBody = sanitizeBody(body);

            if (Object.keys(sanitizedBody).length === 0) {
                return jsonResponse({ error: "Requisição inválida" }, 400);
            }

            const inputData = JSON.stringify(sanitizedBody);
            const proc = spawnPythonProcess();

            proc.stdin.write(inputData);
            proc.stdin.flush();
            proc.stdin.end();

            const text = await new Response(proc.stdout).text();
            const errorText = await new Response(proc.stderr).text();
            await proc.exited;

            if (proc.exitCode !== 0) {
                console.error("AI Pipeline process failed", {
                    exitCode: proc.exitCode,
                    stderr: errorText,
                    stdout: text,
                });

                return jsonResponse(
                    {
                        error: "Serviço de IA indisponível. Tente novamente mais tarde.",
                    },
                    502,
                );
            }

            if (errorText) {
                console.error("Python Error:", errorText);
            }

            try {
                return JSON.parse(text);
            } catch {
                console.error("AI Pipeline: Invalid JSON from python process", {
                    text,
                    stderr: errorText,
                });
                return jsonResponse(
                    {
                        error: "Serviço de IA indisponível. Tente novamente mais tarde.",
                    },
                    502,
                );
            }
        } catch (err: any) {
            console.error("AI Pipeline execution failed:", err?.message ?? err);
            return jsonResponse(
                {
                    error: "Serviço de IA indisponível. Tente novamente mais tarde.",
                },
                503,
            );
        }
    })
    .listen(3001);

console.log(
    `🦊 AI Pipeline is running at ${app.server?.hostname}:${app.server?.port}`,
);
