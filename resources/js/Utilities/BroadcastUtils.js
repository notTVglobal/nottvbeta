import dayjs from 'dayjs'
import utc from 'dayjs-plugin-utc'
import timezone from 'dayjs/plugin/timezone'

dayjs.extend(utc)
dayjs.extend(timezone)

export function filterAndSortBroadcasts(broadcasts, nextBroadcastLoaded, userTimezone) {
    const nowInUserTimezone = dayjs().tz(userTimezone);

    return broadcasts
        .filter(broadcast => {
            // Calculate broadcastEndTime for the current broadcast
            const broadcastEndTime = dayjs(broadcast.broadcastDate)
                .add(broadcast.broadcastDetails.duration_minutes, 'minute')
                .utc()
                .tz(userTimezone);

            // Filter out broadcasts that have already ended
            const isAfterNow = nowInUserTimezone.isBefore(broadcastEndTime);

            // Exclude if it matches nextBroadcastLoaded.broadcastDate && broadcastEndTime
            const isNotLoadedBroadcast = !(
                nextBroadcastLoaded &&
                nextBroadcastLoaded.broadcastDate === broadcast.broadcastDate &&
                dayjs(nextBroadcastLoaded.broadcastDate)
                    .add(nextBroadcastLoaded.broadcastDetails?.duration_minutes || 0, 'minute')
                    .utc()
                    .tz(userTimezone)
                    .isSame(broadcastEndTime)
            );

            return isAfterNow && isNotLoadedBroadcast;
        })
        .sort((a, b) => dayjs(a.broadcastDate).tz(userTimezone).diff(dayjs(b.broadcastDate).tz(userTimezone)))
        .map(broadcast => ({
            ...broadcast,
            localDate: dayjs(broadcast.broadcastDate).tz(userTimezone).format(),
        }));
}
