FROM python:3.10-bullseye
ARG TARGETPLATFORM && ENV TARGETPLATFORM=${TARGETPLATFORM:-linux/amd64}

# Download chatbot backend
RUN git clone https://github.com/SE-Stuttgart/kib3_moodle_chatbot_backend.git /chatbot
RUN cd /chatbot && pip install -r requirements_server.txt


# missing libraries for mupdf:
# - pip3 install PyMuPDF
# - if that doesn't work, upgrade pip
# https://stackoverflow.com/questions/26053982/setup-script-exited-with-error-command-x86-64-linux-gnu-gcc-failed-with-exit
# -> works already in container
