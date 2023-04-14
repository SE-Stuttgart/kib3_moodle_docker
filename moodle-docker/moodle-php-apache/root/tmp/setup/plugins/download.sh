# Download plugins (configuration should be done in seperate script, as this step can only be executed after installing moodle - alternative: could set config properties by changing the settings.py file of the plugins)
 
# Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env 

echo "Downloading Format Tiles Plugin..."
cd /var/www/html/course/format && curl "https://moodle.org/plugins/download.php/28650/format_tiles_${PLUGIN_TILES_FORMAT_VERSION}.zip" --output tiles.zip && unzip tiles.zip && rm tiles.zip

echo "Downloading Section Auto Link Filter..."
cd /var/www/html/filter && curl "https://moodle.org/plugins/download.php/19385/filter_sectionnames_${PLUGIN_SECTION_AUTO_LINKING_VERSION}.zip" --output sectionnames.zip && unzip sectionnames.zip && rm sectionnames.zip

if [ "$PLUGIN_AUTOCOMPLETE" = true ]; then
    echo "Downloading plugin autocomplete..."
    git clone https://github.com/SE-Stuttgart/kib3_moodleplugin_autocompleteactivities.git /var/www/html/local/autocompleteactivities
fi


if [ "$PLUGIN_H5P" = true ]; then
    echo "Downloading H5P Plugin..."
    cd /var/www/html/mod && curl "https://moodle.org/plugins/download.php/28179/mod_hvp_${PLUGIN_HVP_VERSION}.zip" --output hvp.zip && unzip hvp.zip && rm hvp.zip
fi

if [ "$PLUGIN_SLIDEFINDER" = true ]; then 
    echo "Downloading Slidefinder Plugin..."
    git clone https://github.com/SE-Stuttgart/kib3_moodleplugin_slidefinder.git /var/www/html/blocks/slidefinder
fi

if [ "$PLUGIN_STUDENT_REPORT_GENERATION" = true ]; then 
    echo "Downloading Student Report Generation Plugin..."
    git clone https://github.com/SE-Stuttgart/kib3_moodleplugin_srg.git /var/www/html/mod/srg
fi


if [ "$PLUGIN_ICECREAMGAME" = true ]; then 
    echo "Downloading Icecreamgame Plugin..."
    git clone https://github.com/SE-Stuttgart/kib3_moodleplugin_icecreamgame.git /var/www/html/mod/icecreamgame
fi


# TODO download course backup from moodle cloud (NOTE: currently not accecssible)