import { defineStore } from "pinia";

type ScrollType = {
  x: number;
  y: number;
};

type StateType = {
  isSpinnerVisible: boolean;
  scrollParams: ScrollType;
  currentScale: number;
  numberFirstWeek: number;
  currentsCountWeeks: number;
  dictionaryFirstDaysWeek: string[];
  dictionaryTimeline: TimelineDictionaryType;
};

type TimelineDictionaryType = {
  [key: string]: number;
};

export const useGlobalStore = defineStore({
  id: "global",
  state: () =>
    ({
      isSpinnerVisible: false,
      currentScale: 100,
      numberFirstWeek: 0,
      dictionaryTimeline: {},
      dictionaryFirstDaysWeek: [],
      currentsCountWeeks: 52,
      scrollParams: {
        x: 0,
        y: 0,
      },
    } as StateType),

  actions: {
    /**
     * Function to change spinner state.
     */
    toogleSpinnerState() {
      this.isSpinnerVisible = !this.isSpinnerVisible;
    },

    /**
     * Function to handle change scroll params.
     * @param {ScrollType} params
     */
    handleChangeScrollParams(params: ScrollType) {
      this.scrollParams = params;
    },

    /**
     * Function to set current weeks
     * @param {number} weeks
     */
    setCurrentWeeks(weeks: number) {
      this.currentsCountWeeks = weeks;
    },

    /**
     * Function to set dictionary for first days of week.
     * @param string[] dictionary
     */
    setDictionaryDaysWeeks(dictionary: string[]) {
      this.dictionaryFirstDaysWeek = dictionary;
    },

    /**
     * Function to set first current week
     * @param {number} weeks
     */
    setFirstWeeks(week: number) {
      this.numberFirstWeek = week;
    },

    /**
     * Function to set dictionary for timeline.
     * @param {TimelineDictionaryType} dictionary
     */
    setDictionaryTimeline(dictionary: TimelineDictionaryType) {
      this.dictionaryTimeline = dictionary;
    },

    /**
     * Function to handle scalle.
     * @param {number} scale
     */
    handleChangeScale(scale: number) {
      this.currentScale = scale;
    },
  },
});
