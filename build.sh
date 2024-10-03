#!/bin/bash

# Define the source and destination directories
SOURCE_DIR="pages"
DEST_DIR="public"

# Create the destination directory if it doesn't exist
mkdir -p "$DEST_DIR"

# Find all index.php files in the source directory
find "$SOURCE_DIR" -name "index.php" | while read -r file; do
    # Get the relative path of the file
    relative_path="${file#$SOURCE_DIR/}"

    # Get the directory of the file
    dir=$(dirname "$relative_path")

    # Create the corresponding directory in the destination directory
    mkdir -p "$DEST_DIR/$dir"

    # Execute the PHP file and capture the output
    output=$(php "$file")

    # Save the output to an HTML file in the destination directory
    echo "$output" > "$DEST_DIR/$dir/index.html"
done
