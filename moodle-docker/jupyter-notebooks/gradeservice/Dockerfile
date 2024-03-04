FROM python:3.11-slim
USER 0

RUN apt-get update && apt-get install -y \
    git \
    curl \
    ca-certificates \
    gpg
# USER 1001

RUN git clone https://github.com/SE-Stuttgart/jupyterhub-gradeservice.git /jupyter

# Install gradeservice
WORKDIR /jupyter/gradeservice

RUN pip install . --no-cache-dir


CMD ["/bin/bash", "-c", "uvicorn gradeservice.main:app --host 0.0.0.0 --port 5000"]