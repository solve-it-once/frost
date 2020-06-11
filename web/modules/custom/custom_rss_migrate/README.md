# README - Custom RSS Migrate

This module will take an XML file named `sites/default/files/data/rss.pre-sanitized.xml` and import it into the `article` 
content type, using a few safe assumptions:

  1. An `article` content type exists
  2. Articles have `field_tag`, which is a taxonomy term entity reference field to a vocabulary with a machine name of `tag`
  3. Migrated articles should be published
  4. It's ok for migrated articles to be set to the admin user for their author at import-time
  5. The RSS body markup has either been sanitized ahead of time to match the destination, or will be massaged after import

The following are some useful Regex find/replace patterns for sanitizing HTML markup in an RSS file (best results in Sublime 
Text 3):

