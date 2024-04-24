import os
import time

def delete_old_files(directory, age_in_hours=72):
    """Delete files older than a specified age in hours."""
    # Calculate age in seconds
    age_in_seconds = age_in_hours * 3600
    # Current time
    now = time.time()
    # Loop through all files in the directory
    for filename in os.listdir(directory):
        file_path = os.path.join(directory, filename)
        # Check if the file is older than the specified age
        if os.stat(file_path).st_mtime < now - age_in_seconds:
            # Only delete if it's a file
            if os.path.isfile(file_path):
                os.remove(file_path)
                print(f"Deleted {file_path}")

# Path to the directory
directory_path = '/media/recordings_backups'
delete_old_files(directory_path)