(window as any).gallery = function (source: any, imgId: any) {
  // Get the expanded image
  const expandImg = document.getElementById(imgId) as HTMLImageElement;
  // Use the same src in the expanded image as the image being clicked on from the grid
  expandImg.src = source;
}
