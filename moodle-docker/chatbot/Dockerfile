FROM sheogorath15/kib3:chatbot-base-v1
ARG TARGETPLATFORM && ENV TARGETPLATFORM=${TARGETPLATFORM:-linux/amd64}

# Update chatbot backend
RUN cd chatbot/ && git pull

# copy SSL certificates
COPY ./*.crt /etc/ssl/certs/
COPY ./*.key /etc/ssl/private/
