Build & export for multi-platform (ARM64, AMD64):

1. Create & activate custom builder: `docker buildx create --name kib3builder --bootstrap --use`
2. Build & push for multi-platform: `docker buildx build --platform linux/amd64,linux/arm64 -t sheogorath15/kib3:webserver-base-v1 --push .`