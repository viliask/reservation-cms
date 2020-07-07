import { tns } from 'tiny-slider/src/tiny-slider'

const slider = tns({
    container: '.my-slider',
    items: 1,
    slideBy: 'page',
    mouseDrag: true,
    navAsThumbnails: true,
    autoplay: true,
    autoplayTimeout: 5000,
    swipeAngle: false,
    speed: 800,
    navPosition: 'bottom',
    controlsPosition: 'bottom',
    autoplayPosition: 'bottom',
    autoplayText: ['▶', '❚❚'],
    arrowKeys: true,
});
