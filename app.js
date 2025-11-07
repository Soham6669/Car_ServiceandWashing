// Register the ScrollTrigger plugin with GSAP
// NOTE: gsap and ScrollTrigger must be loaded via script tags in index.html before this file runs.
gsap.registerPlugin(ScrollTrigger);

// --- INITIAL POSITION SET (Relying on CSS `right: -500px;` to place them off-screen) ---
// We don't need to use gsap.set here since the CSS already places them off-screen using `right`.

// --- MAIN SCROLLTRIGGER TIMELINE ---

const carJourney = gsap.timeline({
    // Link the entire timeline to the scrolling of the body
    scrollTrigger: {
        trigger: "#scroll-sections",
        start: "top top", // When the top of the scroll container hits the top of the viewport
        end: "bottom bottom", // Until the bottom of the scroll container hits the bottom of the viewport
        scrub: 1, // Smoothly link the animation progress to the scroll position
    }
});

// 1. SUN TRAVEL: Moves slowly from right to left across the entire scroll duration
// duration: 200 is chosen to be much longer than the scroll container height (400vh) 
// relative to the car speed, making the sun appear to move very slowly.
carJourney.to("#sun", {
    x: '-100vw', // Moves the sun entirely off the left side of the screen
    duration: 200, 
    ease: "none" // Linear movement directly tied to scroll position
}, 0); // Start at the very beginning of the timeline (position 0)
// 1. CONTINUOUS TRAVEL: Cars move from their starting position (off right) to off left (-150vw).
// The duration is varied slightly to make them appear to move at different speeds.

// Group 1: Original 3 cars
// Car 3 (Closest lane, largest emoji) moves slightly fastest
carJourney.to("#car3", { 
    x: '-150vw', // Moves the car entirely off the left side of the screen
    duration: 90, // Faster duration relative to the others
    ease: "none" // Linear movement directly tied to scroll position
}, 0); // Start at the beginning (0 seconds/position in the timeline)

// Car 2 (Middle lane) moves at a medium pace
carJourney.to("#car2", { 
    x: '-150vw', 
    duration: 100, // Medium duration
    ease: "none"
}, 0); 

// Car 1 (Furthest lane, smallest emoji) moves slightly slowest
carJourney.to("#car1", { 
    x: '-150vw', 
    duration: 110, // Slower duration relative to the others
    ease: "none"
}, 0); 

// Group 2: Three new cars, slightly different speeds for variety
// Car 4 (A bit faster than Car 2)
carJourney.to("#car4", { 
    x: '-150vw', 
    duration: 130, // Medium-fast
    ease: "none"
}, 0.2); // Start slightly delayed (0.2 units) to prevent all cars from starting in a perfect line

// Car 5 (A bit slower than Car 1)
carJourney.to("#car5", { 
    x: '-150vw', 
    duration: 170, // Very slow
    ease: "none"
}, 0.1); // Start slightly delayed (0.1 units)

// Car 6 (Very fast)
carJourney.to("#car6", { 
    x: '-150vw', 
    duration: 210, // Fastest
    ease: "none"
}, 0.3); // Start slightly delayed (0.3 units)


// --- FINAL ACTION: LEAVE SCENE AND REVEAL HOMEPAGE ---

// Start fading out the scene when the scroll is near the end.
carJourney.to("#animation-wrapper", { 
    opacity: 0, 
    duration: 0.5 
}, "-=2"); // Start fade out 2 timeline units before the end 

// Reveal the "Home Page" content 
carJourney.to("#final-page-content", {
    opacity: 1,
    pointerEvents: 'auto',
    duration: 1
}, "<"); // Start at the same time as the scene fade-out
