# Download plugins (configuration should be done in seperate script, as this step can only be executed after installing moodle - alternative: could set config properties by changing the settings.py file of the plugins)
 
# Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env 

if [ $PLUGIN_AUTOCOMPLETE  = true ]; then
    echo "Downloading plugin autocomplete..."
    git clone https://github.com/SE-Stuttgart/kib3_moodleplugin_autocompleteactivities.git ./local/autocompleteactivities
fi

echo "Downloading Format Tiles Plugin..."
cd "${MOODLE_DOCKER_WWWROOT}/course/format" && curl "https://moodle.org/plugins/download.php/28650/format_tiles_${PLUGIN_TILES_FORMAT_VERSION}.zip" --output tiles.zip && unzip tiles.zip && rm tiles.zip

echo "Downloading H5P Plugin..."
cd "${MOODLE_DOCKER_WWWROOT}/mod" && curl "https://moodle.org/plugins/download.php/28179/mod_hvp_${PLUGIN_HVP_VERSION}.zip" --output hvp.zip && unzip hvp.zip && rm hvp.zip
