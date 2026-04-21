import { Elysia } from "elysia";

const app = new Elysia()
  .post("/analyze", async ({ body }) => {
    try {
      // Validate and sanitize input - only allow expected fields
      const allowedFields = ['text', 'command'];
      const sanitizedBody: Record<string, unknown> = {};
      
      if (typeof body === 'object' && body !== null) {
        for (const [key, value] of Object.entries(body)) {
          if (allowedFields.includes(key)) {
            // Sanitize text field to prevent injection
            if (key === 'text' && typeof value === 'string') {
              sanitizedBody[key] = value.slice(0, 10000); // Limit length
            } else if (key === 'command' && typeof value === 'string') {
              // Whitelist valid commands
              const validCommands = ['analyze', 'seo_title', 'seo_description', 'summary', 'description', 'cta', 'business_audit'];
              if (validCommands.includes(value)) {
                sanitizedBody[key] = value;
              } else {
                sanitizedBody[key] = 'analyze';
              }
            } else {
              sanitizedBody[key] = value;
            }
          }
        }
      }
      
      const inputData = JSON.stringify(sanitizedBody);
      
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
