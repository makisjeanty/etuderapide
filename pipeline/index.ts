import { Elysia } from "elysia";

const app = new Elysia()
  .post("/analyze", async ({ body }) => {
    try {
      const inputData = JSON.stringify(body || {});
      
      // Spawn do processo Python
      const proc = Bun.spawn(["python", "python/analyzer.py"], {
        stdin: "pipe",
        stdout: "pipe",
        stderr: "pipe",
      });

      // Escreve os dados no stdin do Python
      proc.stdin.write(inputData);
      proc.stdin.flush();
      proc.stdin.end();

      // Lê a saída
      const text = await new Response(proc.stdout).text();
      const errorText = await new Response(proc.stderr).text();

      if (errorText) {
        console.error("Python Error:", errorText);
      }

      try {
          return JSON.parse(text);
      } catch (e) {
          return { error: "Failed to parse python output", raw: text, stderr: errorText };
      }
    } catch (err: any) {
      return { error: "Failed to execute python process", message: err.message };
    }
  })
  .listen(3001);

console.log(`🦊 AI Pipeline is running at ${app.server?.hostname}:${app.server?.port}`);
