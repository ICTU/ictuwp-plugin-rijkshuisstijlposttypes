


# sh '/Users/paul/shared-paul-files/Webs/git-repos/Digitale-Overheid---WordPress-Custom-Post-Types-and-Taxonomies/distribute.sh' &>/dev/null

echo '----------------------------------------------------------------';
echo 'Distribute DO post type plugin';




# clear the log file
> '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/development/wp-content/debug.log'

# copy to temp dir
rsync -r -a --delete '/Users/paul/shared-paul-files/Webs/git-repos/Digitale-Overheid---WordPress-Custom-Post-Types-and-Taxonomies/' '/Users/paul/shared-paul-files/Webs/temp/'

# clean up temp dir
rm -rf '/Users/paul/shared-paul-files/Webs/temp/.git/'
rm '/Users/paul/shared-paul-files/Webs/temp/.gitignore'
rm '/Users/paul/shared-paul-files/Webs/temp/.config.codekit3'
rm '/Users/paul/shared-paul-files/Webs/temp/config.codekit3'
rm '/Users/paul/shared-paul-files/Webs/temp/distribute.sh'
rm '/Users/paul/shared-paul-files/Webs/temp/README.md'
rm '/Users/paul/shared-paul-files/Webs/temp/LICENSE'


cd '/Users/paul/shared-paul-files/Webs/temp/';
find . -name '*.map' -type f -delete;
find . -name '.DS_Store' -type f -delete


# --------------------------------------------------------------------------------------------------------------------------------
# Vertalingen --------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------
# remove the .pot
rm '/Users/paul/shared-paul-files/Webs/temp/languages/ictuwp-plugin-rijkshuisstijlposttypes.pot'

# copy files to /wp-content/languages/themes

rsync -ah '/Users/paul/shared-paul-files/Webs/temp/languages/' '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/development/wp-content/languages/plugins/'


# languages Sentia accept
rsync -ah '/Users/paul/shared-paul-files/Webs/temp/languages/' '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/sentia/accept/www/wp-content/languages/plugins/'

# languages Sentia live
rsync -ah '/Users/paul/shared-paul-files/Webs/temp/languages/' '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/sentia/live/www/wp-content/languages/plugins/'
		


# copy from temp dir to dev-env
rsync -r -a --delete '/Users/paul/shared-paul-files/Webs/temp/' '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/development/wp-content/plugins/ictuwp-plugin-rijkshuisstijlposttypes/' 

# en een kopietje naar Sentia accept
rsync -r -a --delete '/Users/paul/shared-paul-files/Webs/temp/' '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/sentia/accept/www/wp-content/plugins/ictuwp-plugin-rijkshuisstijlposttypes/'

# en een kopietje naar Sentia live
rsync -r -a --delete '/Users/paul/shared-paul-files/Webs/temp/' '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/sentia/live/www/wp-content/plugins/ictuwp-plugin-rijkshuisstijlposttypes/'

# remove temp dir
rm -rf '/Users/paul/shared-paul-files/Webs/temp/'

echo 'Ready';
echo '----------------------------------------------------------------';
