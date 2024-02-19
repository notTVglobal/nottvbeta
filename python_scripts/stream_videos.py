import os
import subprocess

# tec21 2024-02-18: I set this up on the Mist Server
# to stream to itself. This makes a good backup stream.
# Replace STREAM with MistServer stream name.
# Replace IP 0.0.0.0 with IP of the Mist Server.
# Change USER to whichever user account will run the script.
# See the documentation for details about running this as a system service.
# If not running the Mist Server on the same host, change the latency to
# at least 4x RTT between source and destination.

# Configuration
VIDEO_DIR = "/home/USER/dir_to_videos"
SRT_URL = "srt://0.0.0.0:8889?mode=caller&streamid=STREAM&pbkeylen=16&latency=200"
LOG_FILE = "/home/USER/last_streamed_video.log"

def log_last_streamed_video(video_name):
    with open(LOG_FILE, 'w') as log_file:
        log_file.write(video_name)

def get_last_streamed_video():
    if os.path.exists(LOG_FILE):
        with open(LOG_FILE, 'r') as log_file:
            return log_file.read().strip()
    return None

def stream_videos():
    last_streamed_video = get_last_streamed_video()
    videos = sorted([v for v in os.listdir(VIDEO_DIR) if v.endswith(".mp4")])

    if last_streamed_video:
        try:
            last_index = videos.index(last_streamed_video)
        except ValueError:  # last_streamed_video not in list
            last_index = -1  # Start from the first video if not found

        # Check if the last_streamed_video is the last in the list
        if last_index + 1 == len(videos):
            last_index = -1  # Set to -1 to loop back to the first video
    else:
        # If no last streamed video is found, start from the beginning
        last_index = -1

    videos_to_stream = videos[last_index + 1:]  # Determines the list of videos to stream

    for video_file in videos_to_stream:
        video_path = os.path.join(VIDEO_DIR, video_file)
        print(f"Streaming {video_file} to {SRT_URL}")
        subprocess.run([
            "ffmpeg", "-re", "-i", video_path,
            "-c:v", "copy",  # Copy video stream without re-encoding
            "-c:a", "aac", "-b:a", "280k", "-ar", "48000",  # Transcode audio
            "-af", "loudnorm,acompressor=threshold=-24dB:ratio=4:attack=5:release=50:knee=2.5:makeup=8,aformat=channel_layouts=mono",
            "-f", "mpegts", SRT_URL
        ])
        # Log this video as the last streamed
        log_last_streamed_video(video_file)

    # If we've reached the end of the list, loop back to the start by calling stream_videos again
    if len(videos_to_stream) > 0 and videos_to_stream[-1] == videos[-1]:
        log_last_streamed_video("")  # Reset the log file to start from the first video next time
        stream_videos()  # Recursively call stream_videos to start over

if __name__ == "__main__":
    stream_videos()