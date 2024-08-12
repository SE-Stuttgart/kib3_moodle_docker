FROM jupyterhub/jupyterhub:4.0.1

RUN apt-get update && apt-get install -y git

RUN git clone https://github.com/SE-Stuttgart/jupyterhub-gradeservice.git /jupyter
RUN pip install --no-cache-dir -r /jupyter/jupyterhub/requirements.txt

ARG MOODLE_SERVER_URL
RUN sed -i "s#\"Content-Security-Policy\": \"\(.*\)\"#\"Content-Security-Policy\": \"\1 ${MOODLE_SERVER_URL%:*}\"#" /jupyter/jupyterhub/jupyterhub_config.py



