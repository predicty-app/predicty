import type { AdNonParsedType } from "@/services/collections";
import type { CampaignType, AdSetsType, AdsType } from "@/stores/userDashboard";

type FirstLastDateType = {
  first: string;
  last: string;
};

type NextDayDictionary = {
  [key: string]: number;
};

/**
 * Function to check is collection exist.
 * @param {CampaignType[]} list
 * @returns {CampaignType[]}
 */
export function hCheckIsCollectionExist(list: CampaignType[]): CampaignType[] {
  if (list.length === 0) {
    return [];
  }

  if (list[0].isCollection && list[0].adsets.length === 0) {
    list.shift();
    return list;
  } else {
    return list;
  }
}

/**
 * Function to get first and last date from list.
 * @param {AdsType[]} list
 * @returns {FirstLastDateType}
 */
export function hFirstAndLastAdsetDate(list: AdsType[]): FirstLastDateType {
  let firstDate = Date.parse(list[0].start) / 1000;
  let lastDate = Date.parse(list[0].end) / 1000;

  let firstDateString = list[0].start;
  let lastDateString = list[0].end;

  list.forEach((ad: AdsType) => {
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

  return { first: firstDateString, last: lastDateString };
}

/**
 * Function to get first and last date from list.
 * @param {CampaignType[] | AdNonParsedType[]} list
 * @returns {FirstLastDateType}
 */
export function hFirstAndLastDate(
  list: CampaignType[] | AdNonParsedType[]
): FirstLastDateType {
  if (list.length === 0) {
    return { first: "2020-01-01", last: "2020-01-01" };
  }

  let firstDate =
    Date.parse(
      (<CampaignType[]>list)[0].adsets
        ? (<CampaignType[]>list)[0].adsets[0].start
        : (<AdNonParsedType[]>list)[0].adStats[0].date
    ) / 1000;
  let lastDate =
    Date.parse(
      (<CampaignType[]>list)[0].adsets
        ? (<CampaignType[]>list)[0].adsets[0].end
        : (<AdNonParsedType[]>list)[0].adStats[
            (<AdNonParsedType[]>list)[0].adStats.length - 1
          ].date
    ) / 1000;

  let firstDateString = (<CampaignType[]>list)[0].adsets
    ? (<CampaignType[]>list)[0].adsets[0].start
    : (<AdNonParsedType[]>list)[0].adStats[0].date;
  let lastDateString = (<CampaignType[]>list)[0].adsets
    ? (<CampaignType[]>list)[0].adsets[0].end
    : (<AdNonParsedType[]>list)[0].adStats[
        (<AdNonParsedType[]>list)[0].adStats.length - 1
      ].date;

  list.forEach((element: CampaignType | AdNonParsedType) => {
    if ((<CampaignType>element).adsets) {
      (<CampaignType>element).adsets.forEach((adset: AdSetsType) => {
        const start = Date.parse(adset.start) / 1000;
        const end = Date.parse(adset.end) / 1000;

        if (start < firstDate) {
          firstDate = start;
          firstDateString = adset.start;
        }

        if (end > lastDate) {
          lastDate = end;
          lastDateString = adset.end;
        }
      });
    } else {
      const parsed = (<AdNonParsedType>element).adStats;

      const start = Date.parse(parsed[0].date) / 1000;
      const end = Date.parse(parsed[parsed.length - 1].date) / 1000;

      if (start < firstDate) {
        firstDate = start;
        firstDateString = parsed[0].date;
      }

      if (end > lastDate) {
        lastDate = end;
        lastDateString = parsed[parsed.length - 1].date;
      }
    }
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

  for (let i = 2; i <= range * 7; i++) {
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

  daysDictionary[0] = `${
    firstDay.getDate() < 10 ? `0${firstDay.getDate()}` : firstDay.getDate()
  }.${
    firstDay.getMonth() + 1 < 10
      ? `0${firstDay.getMonth() + 1}`
      : firstDay.getMonth() + 1
  }.${firstDay.getFullYear()}`;

  for (let i = 1; i <= range; i++) {
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
    }.${nextMonday.getFullYear()}`;

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

/**
 * Function to random color.
 * @returns {string}
 */
export function hRandomColor(): string {
  return "#000000".replace(/0/g, function () {
    return (~~(Math.random() * 16)).toString(16);
  });
}

/**
 * Function to get today date.
 * @returns {string}
 */
export function hGetParseDate(date?: string): string {
  const d = date ? new Date(date) : new Date();
  const month = d.getMonth() + 1;
  const day = d.getDate();
  const year = d.getFullYear();

  return [
    year,
    month < 10 ? `0${month}` : month,
    day < 10 ? `0${day}` : day
  ].join("-");
}

/**
 * Function to format date.
 * @param {string} d 
 * @returns {string}
 */
export function hDateFormat(d?: string): string {
  let date = d ? new Date(d) : new Date();

  return `${date.getDate() < 10 ? `0${date.getDate()}` : date.getDate()}.${
    date.getMonth() + 1 < 10 ? `0${date.getMonth() + 1}` : date.getMonth() + 1
  }`;
};

/**
 * 
 * @param date 
 * @param t 
 * @returns 
 */


export function hCommentDate(date: string, t: any): string {
  let today = new Date();
  let difference =
    (today.getTime() - new Date(date).getTime()) / (1000 * 60 * 60 * 24);
  let result = null;

  if (hDateFormat(date) === hDateFormat(today.toString())) {
    result = t("components.user-dashboard.conversation-comments.conversation-comments-window.today");
  } else if (difference >= 1 && difference < 2) {
    result = `1 ${t("components.user-dashboard.conversation-comments.conversation-comments-window.day-ago")}`;
  } else {
    result = `${Math.floor(difference)} ${t(
      "components.user-dashboard.conversation-comments.conversation-comments-window.days-ago"
    )}`;
  }

  return result;
};