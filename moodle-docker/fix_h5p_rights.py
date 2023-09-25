import tarfile
import xml.etree.ElementTree as ET
import shutil
import os

tar_file_path = 'sicherung-moodle2-course-68-v_2.0-beta_zq_ki_und_ml-20230713-1229.mbz'
file_path =  f'{tar_file_path.rstrip(".mbz")}/files.xml'

# Extract the tar file contents to a temporary directory
with tarfile.open(tar_file_path, 'r') as tar:
    tar.extractall(path=tar_file_path.rstrip('.mbz'))

# Parse the XML file
tree = ET.parse(file_path)
root = tree.getroot()
# Change userid for h5p related files 
for file_entry in root:
    h5p_entry = False
    for sub_child in file_entry:
        if sub_child.tag == "component" and sub_child.text == "mod_h5pactivity":
            h5p_entry = True
    if h5p_entry:
        for sub_child in file_entry:
            if sub_child.tag == "userid":
                sub_child.text = "$@NULL@$"
tree.write(file_path)

os.chdir(tar_file_path.rstrip('.mbz'))

# Pack the modified files back to the tar file
with tarfile.open(tar_file_path, "w:gz") as tar:
    tar.add(".")

os.chdir("../")
shutil.move(f"{tar_file_path.rstrip('.mbz')}/{tar_file_path}", tar_file_path)
# Remove the temporary directory
shutil.rmtree(tar_file_path.rstrip('.mbz'))

print('File modified successfully.')