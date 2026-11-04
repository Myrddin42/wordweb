https://getbootstrap.com/docs/5.3/getting-started/introduction/

# Creating checksums

openssl sha384 -binary bootstrap.min.css | base64 -w0; echo
