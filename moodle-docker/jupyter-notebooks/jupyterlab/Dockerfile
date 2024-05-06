FROM jupyter/tensorflow-notebook:hub-4.0.1
USER 0

RUN apt-get update && apt-get install -y git

RUN git clone https://github.com/SE-Stuttgart/jupyterhub-gradeservice.git /jupyter
# RUN chown=${NB_UID}:${NB_GID} /jupyter/jupyterlab/requirements.txt
RUN pip install --no-cache-dir --requirement /jupyter/jupyterlab/requirements.txt && \
    fix-permissions "${CONDA_DIR}" && \
    fix-permissions "/home/${NB_USER}"

# Use overrides.json file to change the default settings
RUN mkdir -p ${CONDA_DIR}/share/jupyter/lab/settings
RUN cp /jupyter/jupyterlab/overrides.json ${CONDA_DIR}/share/jupyter/lab/settings/overrides.json

ARG MOODLE_SERVER_URL
RUN sed -i "s#\"Content-Security-Policy\": \"\(.*\)\"#\"Content-Security-Policy\": \"\1 ${MOODLE_SERVER_URL}\"#" /jupyter/jupyterlab/jupyter_notebook_config.py 

RUN jupyter labextension disable "@jupyterlab/apputils-extension:announcements"