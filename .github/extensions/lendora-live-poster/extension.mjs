import { createCanvas, CanvasError } from "@github/copilot-sdk/extension";
import { createServer } from "node:http";
import { readFile } from "node:fs/promises";
import { fileURLToPath } from "node:url";

const extensionDir = fileURLToPath(new URL(".", import.meta.url));
const instances = new Map();

async function openPoster(instanceId) {
  if (typeof instanceId !== "string" || instanceId.length === 0) {
    throw new CanvasError("INVALID_INSTANCE", "A valid canvas instance ID is required.");
  }

  const active = instances.get(instanceId);
  if (active) return active.url;

  let html;
  try {
    html = await readFile(new URL("./poster.html", import.meta.url), "utf8");
  } catch (error) {
    throw new CanvasError("POSTER_ASSET_UNAVAILABLE", `Could not read the poster canvas asset: ${error.message}`);
  }

  const server = createServer((request, response) => {
    if (request.method !== "GET" || (request.url !== "/" && request.url !== "/poster")) {
      response.writeHead(404, { "content-type": "text/plain; charset=utf-8" });
      response.end("Not found");
      return;
    }

    response.writeHead(200, {
      "content-type": "text/html; charset=utf-8",
      "cache-control": "no-store",
      "content-security-policy": "default-src 'self'; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'; frame-src https://lendora.infinityfree.me; img-src 'self' data: https://lendora.infinityfree.me; connect-src 'self';",
      "x-content-type-options": "nosniff",
    });
    response.end(html);
  });

  await new Promise((resolve, reject) => {
    server.once("error", reject);
    server.listen(0, "127.0.0.1", resolve);
  }).catch((error) => {
    throw new CanvasError("POSTER_SERVER_FAILED", `Could not start the poster canvas server: ${error.message}`);
  });

  const address = server.address();
  if (!address || typeof address === "string") {
    server.close();
    throw new CanvasError("POSTER_SERVER_FAILED", "The poster canvas server did not provide a local address.");
  }

  const instance = { server, url: `http://127.0.0.1:${address.port}/` };
  instances.set(instanceId, instance);
  return instance.url;
}

const canvas = createCanvas({
  id: "lendora-live-website-poster",
  description: "Design an A4 LENDORA poster with live views of the hosted website and editable promotional text.",
  open: async ({ instanceId }) => ({ url: await openPoster(instanceId) }),
  onClose: async ({ instanceId }) => {
    const active = instances.get(instanceId);
    if (!active) return;
    instances.delete(instanceId);
    await new Promise((resolve, reject) => {
      active.server.close((error) => (error ? reject(error) : resolve()));
    }).catch((error) => {
      throw new CanvasError("POSTER_SERVER_CLOSE_FAILED", `Could not stop the poster canvas server: ${error.message}`);
    });
  },
});

export default canvas;
