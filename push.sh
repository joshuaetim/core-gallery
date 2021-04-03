#!/bin/bash

git add .
echo "Enter commit name -->"
read commit
git commit -m "$commit"
git branch -M main
git push heroku main
echo "Operation successful"