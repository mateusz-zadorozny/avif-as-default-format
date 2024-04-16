# Set avif as default file format instead of jpg and png

Set quality of the the .avif files in the media settings.

**Requires WordPress > 6.5 to work.**

## Using AVIF images in WordPress

AVIF images work like any other image format in WordPress, with a few important notes:

AVIF in WordPress depends on support in your web server’s image processing library (WordPress has built-in support for both Imagick and LibGD for image processing). You can check for AVIF support in wp-admin by visiting Tools -> Site Health, clicking the “Info” tab and expanding the “Media Handling” section, then finally looking for “AVIF” in the list of supported formats.

If your audience includes a significant number of users on an unsupported browser, either avoid using AVIF images, or enqueue a browser polyfill.

[Read more about Avif WordPress Support](https://make.wordpress.org/core/2024/02/23/wordpress-6-5-adds-avif-support/)