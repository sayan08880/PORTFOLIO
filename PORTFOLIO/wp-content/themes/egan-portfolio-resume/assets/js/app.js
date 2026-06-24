// wait until DOM is ready
document.addEventListener("DOMContentLoaded", function(event){
    window.addEventListener("load", function(e){
        gsap.registerPlugin(SplitText);

        var hero = document.getElementById("hero");
        if(hero) {
            var heroClass = hero.className;
            var heroImgScale;
            switch (heroClass) {
                case "hero br-b hero-center-img":
                    heroImgScale = 1.5;
                    break;
                default:
                    heroImgScale = 0.8;
            }
            //Hero
            gsap.from('.hero__img', {
                scale: heroImgScale,
                stagger: 0.1,
                ease: "back",
                duration: 2,
                scrollTrigger: {
                    trigger: '.hero__img',
                    start: "top 80%",
                    toggleActions: "restart"
                }
            });
        }

        // Heading
        const headings = gsap.utils.toArray(".heading-default__title");
        headings.forEach(element => {
            const text = new SplitText(element, { type: "chars" });
            let chars = text.chars;
            gsap.from(chars, {
                y: 0,
                opacity: 0,
                stagger: 0.1,
                ease: "back",
                duration: 1,
                scrollTrigger: {
                    trigger: element,
                    start: "top 80%",
                    toggleActions: "restart"
                }
            });
        });

        // Heading sub
        const sub_headings = gsap.utils.toArray(".heading-default__sub");
        sub_headings.forEach(element => {
            gsap.from(element, {
                x: 30,
                opacity: 0,
                stagger: 0.1,
                ease: "back",
                duration: 1,
                scrollTrigger: {
                    trigger: element,
                    start: "top 80%",
                    toggleActions: "restart"
                }
            });
        });

        // Project
        var project = document.getElementById("project");
        if(project) {
            let tl = gsap.timeline({
                defaults: {
                    ease: "power1.out",
                    duration: .5
                },
                scrollTrigger: {
                    trigger: '.project__list',
                    start: "top 60%",
                    toggleActions: "restart"
                }
            });

            const project_item = gsap.utils.toArray(".project__item");
            project_item.forEach(element => {
                tl.from(element, {
                    y: 50,
                    opacity: 0
                });
            });
        }

        var resume = document.getElementById("resume");
        if(resume) {
            const percent_item = gsap.utils.toArray(".resume-percent__item");
            percent_item.forEach(element => {
                gsap.from(element, {
                    scrollTrigger: {
                        trigger: element,
                        start: "top 80%",
                        end: "bottom 5%",
                        toggleClass: "is-on"
                    },
                });
            });
        }

        // Example
        // const textElement = document.querySelector(".text-to-scroll");
        // gsap.to(textElement, {
        //     x: 0,
        //     // duration: 1,
        //     scrollTrigger: {
        //         trigger: textElement,
        //         start: "top 80%",
        //         end: "bottom 20%",
        //         scrub: true,
        //         markers: true,
        //         pin: true,
        //         pinSpacing: true,
        //         // end: '+=3500'
        //     },
        // });

    }, false);

});
