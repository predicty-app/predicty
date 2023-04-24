/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: "jit",
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx,vue}"],
  theme: {
    extend: {
      top: {
        dynamic: "var(--top)"
      },
      left: {
        dynamic: "var(--left)",
      },
      width: {
        calc: "calc(100vw)",
        dynamic: "var(--height)",
        max: "calc(100vw + 100vw + 100vw)"
      },
      content: {
        dynamic: "var(--content)",
        calc: "calc(100vw)"
      },
      height: {
        calc: "calc(100vh - 60px)",
        dynamic: "var(--height)"
      },
      gridColumnStart: {
        dynamic: "var(--start)"
      },
      gridColumnEnd: {
        dynamic: "var(--end)"
      }
    },
    colors: {
      modalWindow: {
        mask: {
          background: "#00000030"
        }
      },
      connectMoreMedia: {
        text: "#272727",
        icon: {
          background: "#FFFFFF",
          fill: "#60d073"
        }
      },
      codeForm: {
        dash: {
          background: "#111111"
        }
      },
      multiselect: {
        element: {
          disabled: {
            background: "#fafafa"
          },
          hover: {
            background: "#f1fff4"
          }
        },
        lable: {
          color: "#4E5B72"
        },
        icon: {
          background: "#5CD070",
          color: "#FFFFFF"
        }
      },
      notification: {
        success: "#266c04",
        error: "#da2f2f",
        warning: "#ca7e0b",
        info: "#1881c7",
        text: "#FFFFFF"
      },
      tooltipMessage: {
        background: "#BFBFBFB2"
      },
      zoomScale: {
        text: "#FFFFFF",
        background: "#404040"
      },
      charBarPole: {
        background: {
          primary: "#89B3FF",
          secondary: "#E3E7FF",
          disabled: "#ebebeb",
          active: "#5cd070"
        },
        hover: {
          background: "#4184ff85",
          shadow: "#4184FF",
          disabled: "#c5c5c552",
          active: "#88ef9a"
        }
      },
      chartBar: {
        text: "#B5B5B5",
        lines: "#ebebebdb",
        weeks: {
          text: "#9A9A9A",
          sunday: "#EB7A7A"
        }
      },
      select: {
        input: {
          text: "#4E5B72",
          border: "#EDF0F3",
          background: "#FFFFFF",
          placeholder: "#B5B5B5 "
        },
        overlayer: {
          border: "#EDF0F3"
        },
        options: {
          default: {
            text: "#4E5B72",
            background: "#FFFFFF",
            hover: "#19be3424"
          },
          active: {
            text: "#FFFFFF",
            background: "#19be3424",
            hover: "#19be3424"
          }
        }
      },
      campaign: {
        item: {
          border: "var(--color)",
          header: {
            color: "var(--color)"
          },
          content: {
            color: "#A6A6A6"
          }
        }
      },
      timeline: {
        background: "#f9f9fb",
        lines: "#dcdcdd",
        shadow: "var(--color)",
        collection: {
          count: "#00000057"
        },
        days: {
          text: "#9A9A9A",
          sunday: "#EB7A7A"
        },
        lines: {
          text: "#9A9A9A",
          border: "#dcdcdd",
          background_primary: "#f9f9fb",
          background_secondary: "#f4f4f6"
        },
        item: {
          border: "var(--color)",
          background: "var(--color)"
        }
      },
      checkboxForm: {
        border: "#FFFFFF",
        active: {
          background: "#FFFFFF",
          color: "var(--color)"
        }
      },
      legendDescription: {
        header: "#B5B5B5",
        scale: {
          text: "#B5B5B5"
        },
        border: "#F1F3F6",
        background: "#FFFFFF",
        option: "var(--color)"
      },
      salesNumber: {
        date: "#9A9A9A",
        sale: {
          header: "#4799FF",
          text: "#333333"
        },
        investment: {
          header: "#FFAE4F",
          text: "#333333"
        }
      },
      popover: {
        background: "#FFFFFF"
      },
      menuList: {
        color: "var(--color)",
        text: "#333333",
        hover: {
          background: "#edecec"
        }
      },
      dropdown: {
        background: "#FFFFFF"
      },
      floatingPanel: {
        background: "#404040",
        text: "#FFFFFF",
        close: "#da2f2f",
        button: {
          active: {
            background: "#000000",
            text: "#FFFFFF"
          },
          hover: {
            background: "#ededed"
          }
        }
      },
      header: {
        text: "#646464"
      },
      upload: {
        background: "#FFFFFF",
        border: "#E0E3FA",
        text: "#2E0C63",
        button: {
          background: "#2E0C63",
          text: "#FFFFFF"
        }
      },
      layout: {
        onboarding: {
          background: "#F5F6FD",
          border: "#E0E3FA"
        }
      },
      card: {
        border: {
          default: "#E0E3FA",
          success: "#19BE34"
        },
        background: {
          default: "#FFFFFF",
          success: "#60D073"
        },
        text: {
          default: "#000000",
          success: "#FFFFFF"
        }
      },
      fill: {
        default: "#2E0C63"
      },
      divider: {
        default: "#D9D9D9"
      },
      tag: {
        border: {
          default: "#E0E3FA",
          success: "#19BE34",
          primary: "#19BE34"
        },
        background: {
          default: "#E0E3FA",
          success: "#E9FFF0",
          primary: "#60D073"
        },
        text: {
          default: "#2E0C63",
          success: "#19BE34",
          primary: "#FFFFFF"
        }
      },
      progress: {
        default: "#E0E3FA",
        active: "#2E0C63"
      },
      button: {
        default: "#F5F6FD",
        success: "#60D073",
        border: "#E0E3FA",
        disabled: "#D0D0D0",
        hover: {
          default: "#e4e7f7",
          success: "#41cb59"
        },
        active: {
          default: "#e2e4ec",
          success: "#4cb25d"
        }
      },
      bottombar: {
        providers: {
          text: "#5E5E5E",
          border: "#EDF0F3",
          background: "#FFFFFF",
          hover: {
            border: "#19BE34"
          },
          disabled: {
            border: "#EDF0F3",
            background: "#EDF0F3"
          }
        },
        hover: "#D9D9D980",
        text: "#4E5B72",
        grey: "#EDF0F3",
        dark_grey: "#f4f4f6",
        background: {
          light: "#FFF",
          dark: "rgba(0, 0, 0, 0.03)"
        },
        side: {
          grey: "#9B9B9B",
          background: "#f7f7f7",
          dark_grey: "#5E5E5E"
        }
      },
      text: {
        white: "#FFFFFF",
        input: "#82848A",
        error: "#da2f2f",
        primary: "#2E0C63",
        secondary: "#272727"
      },
      default: {
        border: "#b3b6ba",
        outline: "#b3b6ba"
      },
      logo: {
        green: {
          dark: "#19BE34B2",
          light: "#A0E29E"
        },
        blue: {
          dark: "#195BBEB2",
          light: "#BBE4ED"
        },
        purple: {
          dark: "#BB19BEB2",
          light: "#DBB9EB"
        }
      },
      imports: {
        green: "#80C9B9",
        grey: "#8A8A8A"
      }
    },
    boxShadow: {
      bottombar: "0px -5px 30px rgba(0, 0, 0, 0.15)"
    },
    backgroundImage: {
      light:
        "repeating-linear-gradient( to right, #fff 0 calc((100% / 7) - 1px), rgb(233, 233, 233) calc((100% / 7) - 1px) calc(100% / 7) )",
      dark: "repeating-linear-gradient( to right, #f4f4f6 0 calc((100% / 7) - 1px), rgb(233, 233, 233) calc((100% / 7) - 1px) calc(100% / 7) )",
      one: "repeating-linear-gradient(to right,rgb(233, 233, 233) 0 1px,#fff 1px calc(v-bind(scaleLines) / 7),rgb(233, 233, 233) calc(v-bind(scaleLines) / 7) calc((v-bind(scaleLines) / 7) + 1px),#fff calc((v-bind(scaleLines) / 7) + 1px) calc(2 * (v-bind(scaleLines) / 7)), rgb(233, 233, 233) calc(2 * (v-bind(scaleLines) / 7)) calc(2 * (v-bind(scaleLines) / 7) + 1px),#fff calc(2 * (v-bind(scaleLines) / 7) + 1px) calc(3 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(3 * (v-bind(scaleLines) / 7)) calc(3 * (v-bind(scaleLines) / 7) + 1px),#fff calc(3 * (v-bind(scaleLines) / 7) + 1px) calc(4 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(4 * (v-bind(scaleLines) / 7)) calc(4 * (v-bind(scaleLines) / 7) + 1px),#fff calc(4 * (v-bind(scaleLines) / 7) + 1px) calc(5 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(5 * (v-bind(scaleLines) / 7)) calc(5 * (v-bind(scaleLines) / 7) + 1px),#fff calc(5 * (v-bind(scaleLines) / 7) + 1px) calc(6 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(6 * (v-bind(scaleLines) / 7)) calc(6 * (v-bind(scaleLines) / 7) + 1px),#fff calc(6 * (v-bind(scaleLines) / 7) + 1px) calc(7 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(7 * (v-bind(scaleLines) / 7)) calc(7 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(7 * (v-bind(scaleLines) / 7) + 1px) calc(8 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(8 * (v-bind(scaleLines) / 7)) calc(8 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(8 * (v-bind(scaleLines) / 7) + 1px) calc(9 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(9 * (v-bind(scaleLines) / 7)) calc(9 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(9 * (v-bind(scaleLines) / 7) + 1px) calc(10 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(10 * (v-bind(scaleLines) / 7)) calc(10 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(10 * (v-bind(scaleLines) / 7) + 1px) calc(11 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(11 * (v-bind(scaleLines) / 7)) calc(11 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(11 * (v-bind(scaleLines) / 7) + 1px) calc(12 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(12 * (v-bind(scaleLines) / 7)) calc(12 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(12 * (v-bind(scaleLines) / 7) + 1px) calc(13 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(13 * (v-bind(scaleLines) / 7)) calc(13 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(13 * (v-bind(scaleLines) / 7) + 1px) calc(14 * (v-bind(scaleLines) / 7)))"
    }
  },
  variants: {
    extend: {}
  },
  plugins: []
};
