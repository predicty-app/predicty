import * as d3Shape from "d3-shape";
import * as d3Selection from "d3-selection";
import {
  getScale,
  scaleCharDaystLines,
  scaleCharWeekstLines
} from "@/helpers/timeline";
import {
  useUserDashboardStore,
  TypeOptionsChart,
  type TooltipType
} from "@/stores/userDashboard";

const userDashboardStore = useUserDashboardStore();

/**
 * Function to calculate line position.
 * @param {'x' | 'y'} type
 * @param {number} index
 * @param {number} value
 * @return {number}
 */
function calculateLinePosition(
  type: "x" | "y",
  index: number,
  value?: number
): number {
  const scale =
    userDashboardStore.typeChart === TypeOptionsChart.DAYS
      ? scaleCharDaystLines.value
      : scaleCharWeekstLines.value;

  switch (type) {
    case "x": {
      return scale * index + scale / 2;
    }
    case "y": {
      return value * getScale();
    }
  }
}

/**
 * Function to calculate line points.
 * @param {number[]} values
 * @return {number[][]}
 */
function calculatePoints(values: number[]): number[][] {
  const points = [];
  const scale =
    userDashboardStore.typeChart === TypeOptionsChart.DAYS
      ? scaleCharDaystLines.value
      : scaleCharWeekstLines.value;
  points.push([0 - scale / 2, values[0] * getScale()]);

  for (let i = 0; i < values.length; i++) {
    points.push([
      calculateLinePosition("x", i),
      calculateLinePosition("y", i, values[i])
    ]);
  }

  return points;
}

/**
 * Function to draw line chart.
 * @param {string} pathId
 * @param {number[]} values
 */
function drawLine(pathId: string, values: number[]) {
  const lineGenerator = d3Shape
    .line()
    .curve(d3Shape.curveCatmullRom.alpha(0.5));
  const pathData = lineGenerator(calculatePoints(values));
  d3Selection.select(`#${pathId}`).attr("d", pathData);
}

/**
 * Function to draw pointer.
 * @param {string} svgId
 * @param {string} pointerId
 * @param {number[]} values
 */
function drawPointer(
  svgId: string,
  pointerId: string,
  tooltipId: string,
  values: number[],
  tooltipData: TooltipType[]
) {
  const svg = d3Selection.select(`#${svgId}`);
  const pointer = d3Selection.select(`#${pointerId}`);
  const tooltip = d3Selection.select(`#${tooltipId}`);

  d3Selection.select(`#${pointerId} rect`).remove();
  d3Selection.select(`#${pointerId} circle`).remove();
  svg.on("mouseover", null);
  svg.on("mousemove", null);
  svg.on("mouseout", null);

  pointer
    .append("rect")
    .attr("width", 2)
    .attr("x", -1)
    .attr("height", "100%")
    .attr("fill", "#D2D0D7");
  pointer
    .append("circle")
    .attr("r", 8)
    .attr("stroke", "#fff")
    .attr("fill", "#6E7DD9")
    .attr("stroke-width", 4);

  svg.on("mouseover", function () {
    pointer.style("display", "block");
    tooltip.style("display", "block");
  });

  svg.on("mousemove", function (mouse) {
    const x = d3Selection.pointer(mouse)[0];
    const closest = calculatePoints(values).reduce((prev, curr) =>
      Math.abs(curr[0] - x) < Math.abs(prev[0] - x) ? curr : prev
    );
    const closestDataIndex =
      calculatePoints(values).findIndex((v) => v[0] === closest[0]) - 1;

    pointer.select("circle").attr("cx", closest[0]).attr("cy", closest[1]);
    pointer.select("rect").attr("x", closest[0] - 1);
    tooltip.style("left", `${closest[0] + 8}px`);
    tooltip.style("top", "0px");
    if (closestDataIndex >= 0) {
      userDashboardStore.currentTooltip.date =
        tooltipData[closestDataIndex].date;
      userDashboardStore.currentTooltip.sales =
        tooltipData[closestDataIndex].sales;
      userDashboardStore.currentTooltip.investment =
        tooltipData[closestDataIndex].investment;
    }
  });

  svg.on("mouseout", function () {
    pointer.style("display", "none");
    tooltip.style("display", "none");
  });
}

function clearLine(pathId) {
  d3Selection.select(`#${pathId}`).attr("d", "");
}

function toggleBars(show: boolean) {
  d3Selection.selectAll(".bar").style("display", `${show ? "block" : "none"}`);
}

export { drawLine, drawPointer, clearLine, toggleBars };
