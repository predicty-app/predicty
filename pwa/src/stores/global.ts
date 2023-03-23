import { defineStore } from "pinia";

type ScrollType = {
  x?: number;
  y?: number;
};

type StateType = {
  currentScale: number;
  numberFirstWeek: number;
  scrollParams: ScrollType;
  isSpinnerVisible: boolean;
  currentsCountWeeks: number;
  wrapperPole: HTMLDivElement | null;
  dictionaryFirstDaysWeek: string[];
  scrollTimeline: HTMLDivElement | null;
  scrollCampaignList: HTMLDivElement | null;
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
      wrapperPole: null,
      scrollTimeline: null,
      scrollCampaignList: null,
      dictionaryTimeline: {},
      dictionaryFirstDaysWeek: [],
      currentsCountWeeks: 52,
      scrollParams: {
        x: 0,
        y: 0
      }
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
      this.scrollParams = {
        ...params
      };
    },

    /**
     * Function to set current weeks
     * @param {number} weeks
     */
    setCurrentWeeks(weeks: number) {
      this.currentsCountWeeks = weeks;
    },

    /**
     * Function to set timeline scroll element.
     * @param {HTMLDivElement} element
     */
    setInstanceScrollTimeline(element: HTMLDivElement) {
      this.scrollTimeline = element;
    },

    /**
     * Function to set campaigns list scroll element.
     * @param {HTMLDivElement} element
     */
    setInstanceScrollCampaignsList(element: HTMLDivElement) {
      this.scrollCampaignList = element;
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
    }
  }
});
