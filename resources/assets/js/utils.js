import moment from 'moment'

export function fromNow (date) {
  return moment(date, 'YYYY-MM-DD HH:mm').fromNow()
}

export function fileSize (byte) {
  return Math.round(byte / 1024) + ' Kb'
}

export function eventFormat (date) {
  let _date = moment(date, 'YYYY-MM-DD HH:mm')
  return {
    day: _date.format('D'),
    month: _date.format('MMMM')
  }
}

export function elapsedFormat (time, full) {
  if (time < 60 && !full) return '00:01'

  let secNum = parseInt(time, 10)
  let hours = Math.floor(secNum / 3600)
  let minutes = Math.floor((secNum - (hours * 3600)) / 60)
  let seconds = secNum - (hours * 3600) - (minutes * 60)

  if (hours < 10) {
    hours = '0' + hours
  }
  if (minutes < 10) {
    minutes = '0' + minutes
  }
  if (seconds < 10) {
    seconds = '0' + seconds
  }

  return hours + ':' + minutes + (full ? ':' + seconds : '')
}

export function dateFormat (date, full) {
  if (!date) {
    return null
  }

  const format = full ? 'HH:mm on MMM DD, YYYY' : 'MMM DD, YYYY'

  if (date && date.isMoment) {
    return date.format(format)
  }
  return moment(date, 'YYYY-MM-DD HH:mm').format(format)
}

export function monthDateFormat (date) {
  return moment(date, 'YYYY-MM-DD').format('MMMM DD.')
}

export function toSeconds (val) {
  const t = val.split(':')
  return t[0] * 3600 + t[1] * 60
}

export function strToInterval (date) {
  let start, end
  switch (date) {
    case 'thisWeek':
      start = moment().startOf('isoWeek')
      end = moment().endOf('isoWeek')
      break
    case 'nextWeek':
      start = moment().add(1, 'weeks').startOf('isoWeek')
      end = moment().add(1, 'weeks').endOf('isoWeek')
      break
    case 'thisMonth':
      start = moment().startOf('month')
      end = moment().endOf('month')
      break
    case 'nextMonth':
      start = moment().add(1, 'months').startOf('month')
      end = moment().add(1, 'months').endOf('month')
      break
  }
  return [start, end]
}