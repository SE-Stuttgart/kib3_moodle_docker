FROM python:3.10-bullseye
ARG TARGETPLATFORM && ENV TARGETPLATFORM=${TARGETPLATFORM:-linux/amd64}

# Set the locale to German (de_DE)
RUN apt-get update && apt-get install -y locales && rm -rf /var/lib/apt/lists/* \
    && sed -i -e 's/# de_DE.UTF-8 UTF-8/de_DE.UTF-8 UTF-8/' /etc/locale.gen \
    && dpkg-reconfigure --frontend=noninteractive locales \
    && update-locale LANG=de_DE.UTF-8

# Set the default environment variable for the locale
ENV LANG de_DE.UTF-8

# base requirements: rust
RUN curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh -s -- -y
ENV PATH="/root/.cargo/bin:${PATH}"

# update pip
RUN pip install --upgrade pip

# Download chatbot backend
RUN git clone https://github.com/SE-Stuttgart/kib3_moodle_chatbot_backend.git /chatbot
RUN cd /chatbot && pip install -r requirements_server.txt
