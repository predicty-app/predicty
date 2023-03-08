import type { CampaignType } from "@/stores/userDashboard";

type FirstLastDateType = {
  first: string;
  last: string;
};

type NextDayDictionary = {
  [key: string]: number;
};

/**
 * Function to get first and last date from list.
 * @param {}CampaignType[]} list
 * @returns {FirstLastDateType}
 */
export function hFirstAndLastDate(list: CampaignType[]): FirstLastDateType {
  let firstDate = Date.parse(list[0].ads[0].start) / 1000;
  let lastDate = Date.parse(list[0].ads[0].end) / 1000;

  let firstDateString = list[0].ads[0].start;
  let lastDateString = list[0].ads[0].end;

  list.forEach((campaing) => {
    campaing.ads.forEach((ad) => {
      const start = Date.parse(ad.start) / 1000;
      const end = Date.parse(ad.end) / 1000;

      if (start < firstDate) {
        firstDate = start;
        firstDateString = ad.start;
      }

      if (end > lastDate) {
        lastDate = end;
        lastDateString = ad.end;
      }
    });
  });

  return { first: firstDateString, last: lastDateString };
}

/**
 * Function to get number weeks from date.
 * @param {string} date
 * @returns {number}
 */
export function hNumberWeekFromDate(date: string): number {
  const d = new Date(date);
  const dayNum = d.getUTCDay() || 7;
  d.setUTCDate(d.getUTCDate() + 4 - dayNum);
  const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
  // @ts-ignore
  return Math.ceil(((d - yearStart) / 86400000 + 1) / 7);
}

/**
 * Function to get weeks beetween two dates.
 * @param {string} dateFirst
 * @param {string} dateLast
 * @returns {number}
 */
export function hWeeksBetween(dateFirst: string, dateLast: string): number {
  return Math.round(
    // @ts-ignore
    (new Date(dateLast) - new Date(dateFirst)) / (7 * 24 * 60 * 60 * 1000)
  );
}

/**
 * Function get first date of week.
 * @param {string} date
 * @param {number} week
 * @returns {Date}
 */
export function hWeekToDate(year: number, week: number): string {
  const d = new Date("Jan 01, " + year + " 01:00:00");
  const dayMs = 24 * 60 * 60 * 1000;
  const offSetTimeStart = dayMs * (d.getDay() - 1);
  const w = d.getTime() + 604800000 * week - offSetTimeStart; //reducing the offset here
  const n1 = new Date(w);

  const month =
    n1.getMonth() < 10 ? `0${n1.getMonth() + 1}` : n1.getMonth() + 1;
  return `${n1.getDate()}.${month}`;
}

/**
 * Function to get by date day of the year.
 * @param {string} date
 * @returns {number}
 */
export function hDayOfYear(date: string): number {
  const now = new Date(date);
  const start = new Date(now.getFullYear(), 0, 0);

  //@ts-ignore
  const diff = now - start;
  const oneDay = 1000 * 60 * 60 * 24;
  return Math.floor(diff / oneDay);
}

/**
 * Function to get first day of week (monday)
 * @param {string} first
 * @returns {Date}
 */
export function hFirstDayWeek(first: string): Date {
  const firstDayWeek = Math.abs(
    1 - (new Date(first).getDay() === 0 ? 7 : new Date(first).getDay())
  );

  const firstDay = new Date(first);
  firstDay.setDate(firstDay.getDate() - firstDayWeek);

  return firstDay;
}

/**
 * Function to get next day by date.
 * @param {Date} firstDay
 * @param {number} range
 * @returns {NextDayDictionary}
 */
export function hNextDaysDictionary(
  firstDay: Date,
  range: number
): NextDayDictionary {
  const daysDictionary = {};
  let firstDateParsed = `${firstDay.getFullYear()}-${
    firstDay.getMonth() + 1 < 10
      ? `0${firstDay.getMonth() + 1}`
      : firstDay.getMonth() + 1
  }-${firstDay.getDate() < 10 ? `0${firstDay.getDate()}` : firstDay.getDate()}`;

  daysDictionary[firstDateParsed] = 1;

  for (let i = 2; i <= (range + 2) * 7; i++) {
    const tomorrowDay = new Date(firstDateParsed);
    tomorrowDay.setDate(tomorrowDay.getDate() + 1);
 
    const parsedDay = `${tomorrowDay.getFullYear()}-${
      tomorrowDay.getMonth() + 1 < 10
        ? `0${tomorrowDay.getMonth() + 1}`
        : tomorrowDay.getMonth() + 1
    }-${
      tomorrowDay.getDate() < 10
        ? `0${tomorrowDay.getDate()}`
        : tomorrowDay.getDate()
    }`;

    daysDictionary[parsedDay] = i;
    firstDateParsed = parsedDay;
  }

  return daysDictionary;
}

/**
 * Function to get first days week.
 * @param {Date} firstDay
 * @param {number} range
 * @returns {string[]}
 */
export function hFirstDaysWeeks(firstDay: Date, range: number): string[] {
  const daysDictionary = [];
  let firstDateParsed = `${firstDay.getFullYear()}-${
    firstDay.getMonth() + 1 < 10
      ? `0${firstDay.getMonth() + 1}`
      : firstDay.getMonth() + 1
  }-${firstDay.getDate() < 10 ? `0${firstDay.getDate()}` : firstDay.getDate()}`;

  daysDictionary[1] = `${
    firstDay.getDate() < 10 ? `0${firstDay.getDate()}` : firstDay.getDate()
  }.${
    firstDay.getMonth() + 1 < 10
      ? `0${firstDay.getMonth() + 1}`
      : firstDay.getMonth() + 1
  }`;

  for (let i = 2; i <= (range + 2) * 7; i++) {
    const nextMonday = new Date(firstDateParsed);
    nextMonday.setDate(nextMonday.getDate() + 7);

    const parsedDay = `${nextMonday.getFullYear()}-${
      nextMonday.getMonth() + 1 < 10
        ? `0${nextMonday.getMonth() + 1}`
        : nextMonday.getMonth() + 1
    }-${
      nextMonday.getDate() < 10
        ? `0${nextMonday.getDate()}`
        : nextMonday.getDate()
    }`;

    daysDictionary[i] = `${
      nextMonday.getDate() < 10
        ? `0${nextMonday.getDate()}`
        : nextMonday.getDate()
    }.${
      nextMonday.getMonth() + 1 < 10
        ? `0${nextMonday.getMonth() + 1}`
        : nextMonday.getMonth() + 1
    }`;
    firstDateParsed = parsedDay;
  }

  return daysDictionary;
}

/**
 * Function to get lighter or darker color.
 * @param {string} color
 * @param {number} amount
 * @returns {string}
 */
export function hLightenDarkenColor(color: string, amount: number): string {
  return (
    "#" +
    color
      .replace(/^#/, "")
      .replace(/../g, (color) =>
        (
          "0" +
          Math.min(255, Math.max(0, parseInt(color, 16) + amount)).toString(16)
        ).substr(-2)
      )
  );
}
