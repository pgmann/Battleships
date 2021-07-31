// TOOLTIPS
// a tooltip will appear just beside the user's mouse when they hover over any element that has a data-title attribute set.
// on mobile (the touch events) the tooltip is shown a bit higher so it isn't obscured by the user's fingers.
var tooltip = document.createElement("div");
var tooltipTarget;
tooltip.id = "tooltip";
tooltip.style.display = "none";
document.body.appendChild(tooltip);

// the following section handles the mouse (or touch) starting to hover over an element with a title.
// the tooltip is shown and the position is set
function startMoveHandler(event) {
  if (event.target == tooltip) return;
  if (event.target.dataset && event.target.dataset.title) {
    // this element has a title, so show the tooltip
    tooltip.style.display = "block";
    tooltip.innerHTML = event.target.dataset.title;
    var coords = event.type.includes("touch") ? event.touches[0] : event;
    tooltip.style.left = (coords.clientX + 10) + "px";
    tooltip.style.top = (coords.clientY + (event.type.includes("touch") ? -100 : 10)) + "px";
    tooltipTarget = event.target;
  } else {
    // the element has no title, don't show
    tooltip.style.display = "none";
    tooltipTarget = null;
  }
}
document.addEventListener("mouseenter", startMoveHandler, true);
document.addEventListener("touchstart", startMoveHandler, true);

// this section handles the mouse moving (in which case the position of the tooltip is updated) and touch moving
// which can jump between multiple tooltips.
function moveHandler(event) {
  if (tooltipTarget != null) {
    // get coordinates of the current event and the element that corresponds to
    var coords = event.type.includes("touch") ? event.touches[0] : event;
    var currentElement = document.elementFromPoint(coords.clientX, coords.clientY);
    if ((currentElement != tooltipTarget || tooltip.style.display == "none") && currentElement != tooltip) {
      if (currentElement && currentElement.dataset && currentElement.dataset.title) {
        // the touch is now over a new element that has a title, redisplay the tooltip with new title
        tooltipTarget = currentElement;
        updateTooltip();
        tooltip.style.display = "block";
      } else if (event.type.includes("touch")) {
        // the touch is NOT over an element with a title, hide the tooltip for now
        tooltip.style.display = "none";
      }
    }
    // normal behaviour, move the tooltip to the updated position (during touch the tooltip appears higher up)
    tooltip.style.left = (coords.clientX + 10) + "px";
    tooltip.style.top = (coords.clientY + (event.type.includes("touch") ? -100 : 10)) + "px";
  }
}
document.addEventListener("mousemove", moveHandler, true);
document.addEventListener("touchmove", moveHandler, true);

// this handles the mouse leaving the element or the touch being released.
function endMoveHandler(event) {
  if (tooltipTarget != null) {
    // hdie the tooltip
    tooltipTarget = null;
    tooltip.style.display = "none";
  }
}
document.addEventListener("mouseleave", endMoveHandler, true);
document.addEventListener("touchend", endMoveHandler, true);

// if the content of a tooltip is updated then this method must be called.
function updateTooltip() {
  if (tooltipTarget != null) {
    tooltip.innerHTML = tooltipTarget.dataset.title;
  }
}