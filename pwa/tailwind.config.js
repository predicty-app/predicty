/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: "jit",
  purge: ["./index.html", "./src/**/*.{js,ts,jsx,tsx,vue}"],
  darkMode: false,
  theme: {
    extend: {
      width: {
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
      charBarPole: {
        background: {
          primary: "#89B3FF",
          secondary: "#E3E7FF"
        },
        hover: {
          background: "#4184FF",
          shadow: "#4184FF"
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
        hover: "#D9D9D980"
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
      }
    },
    boxShadow: {
      bottombar: "0px -5px 30px rgba(0, 0, 0, 0.15)"
    }
  },
  variants: {
    extend: {}
  },
  plugins: []
};
