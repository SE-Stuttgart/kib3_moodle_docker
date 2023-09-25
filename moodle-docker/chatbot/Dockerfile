FROM python:3.10-bullseye
ARG TARGETPLATFORM && ENV TARGETPLATFORM=${TARGETPLATFORM:-linux/amd64}

# base requirements: rust
RUN curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh -s -- -y
ENV PATH="/root/.cargo/bin:${PATH}"

# update pip
RUN pip install --upgrade pip

# Download chatbot backend
RUN git clone https://github.com/SE-Stuttgart/kib3_moodle_chatbot_backend.git /chatbot
RUN cd /chatbot && pip install -r requirements_server.txt


# missing libraries for mupdf:
# - pip3 install PyMuPDF
# - if that doesn't work, upgrade pip
# https://stackoverflow.com/questions/26053982/setup-script-exited-with-error-command-x86-64-linux-gnu-gcc-failed-with-exit
# -> works already in container
