#!/bin/bash

# Get the current date and time
date_time=$(date "+%Y-%m-%d %H:%M:%S")

# Prompt user for commit message
echo "Enter commit message: "
read user_commit_message

# Use user input if provided, otherwise default to auto-generated message
if [ -z "$user_commit_message" ]; then
  commit_message="Auto-commit on $date_time"
else
  commit_message="$user_commit_message"
fi

# Add all changes
git add .

echo "Staging all changes..."

# Commit with the generated message
git commit -m "$commit_message"

echo "Committed with message: '$commit_message'"

# Push to the main branch
git push origin main

echo "Pushed to remote repository."
