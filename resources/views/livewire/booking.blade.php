<div>
    <section class="   w-full    ">
        <!-- code start -->
        <!-- dark bg for elements should be gray-800 -->

        <header class="flex flex-col h-screen  relative   w-full  mx-auto">

            <img class="absolute inset-0 w-full h-full object-cover brightness-50" alt="bedroom"
                src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80">


            <!-- With login logout -->
            <nav x-data="{ open: false }"
                class=" z-10   shadow w-12/12 mx-auto   dark:bg-gray-800/90 shadow mx-auto w-full  px-4">

                <div class=" container mx-auto ">
                    <div class="flex items-center justify-between   py-4">

                        <!-- Brand -->
                        <a class="text-3xl  text-white font-bold  ">
                            HANAPBOK
                        </a>

                        <!-- Navigation Links -->
                        <ul class="hidden md:flex sm:items-center">
                            <li><a
                                    class="cursor-pointer text-gray-100 dark:text-gray-200 dark:hover:text-blue-500 text-sm font-[390] dark:font-normal hover:text-blue-600 mr-4">Services</a>
                            </li>
                            <li><a
                                    class="cursor-pointer text-gray-100 dark:text-gray-200 dark:hover:text-blue-500 text-sm font-[390] dark:font-normal hover:text-blue-600 mr-4">Rooms</a>
                            </li>
                            <li><a
                                    class="cursor-pointer text-gray-100 dark:text-gray-200 dark:hover:text-blue-500 text-sm font-[390] dark:font-normal hover:text-blue-600 mr-4">Breakfast</a>
                            </li>
                            <li><a
                                    class="cursor-pointer text-gray-100 dark:text-gray-200 dark:hover:text-blue-500 text-sm font-[390] dark:font-normal hover:text-blue-600 mr-4">About</a>
                            </li>
                            <li><a class="cursor-pointer text-gray-100 dark:text-gray-200 dark:hover:text-blue-500 text-sm font-[390] dark:font-normal hover:text-blue-600"
                                    wire:click.prevent="redirectDashboard">Back</a>
                            </li>
                        </ul>


                        {{-- <ul class="hidden md:flex sm:items-center">
                            <li>
                                <a href="javascript:void(0)" wire:click="dashboard"
                                    class="cursor-pointer text-gray-100 dark:text-gray-200 text-sm font-[390] dark:font-normal   px-4 py-2 rounded-lg  border hover:border-inherit dark:border-gray-600 hover:text-white hover:bg-gradient-to-r from-cyan-500 to-blue-500 ">Get
                                    started
                                </a>
                            </li>
                        </ul> --}}

                        <!-- Hamburger -->
                        <div @click="open = !open" class="md:hidden cursor-pointer transition-width">
                            <svg stroke="currentColor" fill="none" class="w-6 h-6 text-gray-200 dark:text-gray-300"
                                viewBox="0 0 24 24">
                                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>

                    <!-- Responsive Navigation Menu -->
                    <div :class="{ 'block': open, 'hidden': !open }"
                        class="hidden md:hidden   dark:bg-inherit border-t dark:border-gray-700/70 py-2">
                        <ul class="flex flex-col gap-3">
                            <li><a
                                    class="cursor-pointer flex text-gray-100 dark:text-gray-200 dark:hover:text-blue-500 text-sm font-[390] hover:text-blue-600 mb-1">Services</a>
                            </li>
                            <li><a
                                    class="cursor-pointer flex text-gray-100 dark:text-gray-200 dark:hover:text-blue-500 text-sm font-[390] hover:text-blue-600 mb-1">Rooms</a>
                            </li>
                            <li><a
                                    class="cursor-pointer flex text-gray-100 dark:text-gray-200 dark:hover:text-blue-500 text-sm font-[390] hover:text-blue-600 mb-1">Breakfast</a>
                            </li>
                            <li><a
                                    class="cursor-pointer flex text-gray-100 dark:text-gray-200 dark:hover:text-blue-500 text-sm font-[390] hover:text-blue-600 mb-1">About</a>
                            </li>

                            <div class="flex justify-center items-center border-t-2 dark:border-gray-700/70 py-3">
                                <li><a
                                        class="cursor-pointer text-gray-100 dark:text-gray-200 text-sm font-medium dark:font-normal   px-4 py-2 rounded-lg hover:text-white/90 hover:border-blue-600 dark:border-0 text-white bg-gradient-to-r from-cyan-500 to-blue-500 ">Get
                                        started</a></li>
                            </div>
                        </ul>
                    </div>


                </div>
            </nav>

            <div class="  space-y-7 z-10 mt-32  p-4">

                <h3 class="text-4xl capitalize max-w-sm font-medium text-white">Find your Perfect travel desitination in
                    the Banamas</h3>
                <p class="text-white/90 font-[300] max-w-lg">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Rerum officia natus temporibus dolore veritatis, eos ipsam, laudantium accusamus qui necessitatibus
                    recusandae veniam deserunt illo magnam maxime dolorum nostrum maiores harum.</p>
                <button
                    class="text-lg  font-[390] dark:font-[300] cursor-pointer animate-pulse max-w-fit  p-3 px-6 px-4 bg-gray-100 dark:bg-gray-800  dark:text-white/90 hover:bg-gray-300 hover:text-blue-500 transition-colors    rounded-md">Reservation</button>


            </div>
        </header>


        <br><br><br>


        <section class="w-full  p-5  py-16 relative   justify-start items-center">
            <div class="mx-auto  my-16 text-center space-y-3">
                <h4 class="text-5xl  dark:text-white font-[200]">Our Cuisines Include</h4>
                <p class="text-gray-500 font-[230] dark:text-gray-400 max-w-xl mx-auto">Lorem, ipsum dolor sit amet
                    consectetur adipisicing elit. Perferendis recusandae, tempora nisi consectetur autem explicabo
                    reprehenderit et nesciunt repellendus </p>
            </div>


            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 xl:gap-9">
                <img class="h-96 rounded-sm   object-cover "
                    src="https://images.unsplash.com/photo-1614602638662-c7c1f55c33f9?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8Zm9vZCUyMHBpY3R1cmVzfGVufDB8MHwwfHw%3D&auto=format&fit=crop&w=800&q=60"
                    alt="image">
                <img class="h-96 rounded-sm   object-cover "
                    src="https://images.unsplash.com/photo-1577859714523-5f0b6c98ece7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80"
                    alt="image">
                <img class="h-96 rounded-sm   object-cover "
                    src="https://images.unsplash.com/photo-1567121938596-6d9d015d348b?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8Zm9vZCUyMHBpY3R1cmVzfGVufDB8MHwwfHw%3D&auto=format&fit=crop&w=800&q=60"
                    alt="image">
                <img class="h-96 rounded-sm   object-cover "
                    src="https://images.unsplash.com/photo-1633337474564-1d9478ca4e2e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MzJ8fGZvb2QlMjBwaWN0dXJlc3xlbnwwfDB8MHx8&auto=format&fit=crop&w=800&q=60"
                    alt="image">

            </div>
        </section>



        <br><br><br>


        <!-- banner -->
        <section
            class="grid    h-52 w-11/12 my-12 p-4 mx-auto rounded-lg border border-gray-200  dark:border-gray-700 bg-gradient-to-r from-cyan-500 to-blue-500">
            <div class="grid grid-cols-2   ">
                <aside class=" flex flex-col text-center items-center gap-y-5   p-6">
                    <h3 class="text-5xl font-bold text-white">20% Off</h3>
                    <p class="text-2xl text-white rotate-6">Today</p>

                </aside>

                <aside class=" flex flex-col my-auto    justify-center text-center items-center    p-6">

                    <p class="text-2xl text-white   capitalize">On the holiday season</p>

                </aside>

            </div>

        </section>


        <!-- rooms -->
        <br><br><br>

        <section class="grid md:grid-cols-2 gap-3 w-full p-4 xl:px-14 container mx-auto  ">
            <img class="  h-80  w-full max-w-2xl mx-auto rounded-sm border object-cover dark:border-gray-700 bg-gray-100"
                src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80"
                alt="image">

            <aside class="space-y-5 my-auto">
                <h4 class="text-blue-500"> Romms</h4>
                <h2 class="text-2xl font-bold dark:text-gray-300">Family Bedroom</h2>

                <p class=" dark:text-gray-300">Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores magni
                    ea aliquid nemo. Voluptatum laborum illum esse quas aliquam vel culpa voluptas libero magni vitae
                    modi maiores dolore, ullam praesentium?</p>

                <button
                    class="px-7 my-5 py-2 bg-blue-600 hover:bg-blue-500 text-center max-h-fit    rounded-sm text-sm  font-normal   text-white capitalize transition-colors ">
                    More
                </button>
            </aside>
        </section>

        <br><br>

        <section class="grid md:grid-cols-2 gap-3 w-full p-4 xl:px-14 container mx-auto  ">
            <aside class="space-y-5 my-auto">
                <h4 class="text-blue-500"> Rooms</h4>
                <h2 class="text-2xl font-bold dark:text-gray-300">Economic bedroom</h2>

                <p class=" dark:text-gray-300">Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores magni
                    ea aliquid nemo. Voluptatum laborum illum esse quas aliquam vel culpa voluptas libero magni vitae
                    modi maiores dolore, ullam praesentium?</p>

                <button
                    class="px-7 my-5 py-2 bg-blue-600 hover:bg-blue-500 text-center max-h-fit    rounded-sm text-sm  font-normal   text-white capitalize transition-colors ">
                    More
                </button>
            </aside>

            <img class="  h-80  w-full max-w-2xl mx-auto rounded-sm border object-cover dark:border-gray-700 bg-gray-100"
                src="https://images.unsplash.com/photo-1595576508898-0ad5c879a061?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1374&q=80"
                alt="image">

        </section>



        <br><br><br>



        <!-- section  -->
        <section class="w-full  p-5  py-16 relative   justify-start items-center">


            <div class="mx-auto  my-16 text-center space-y-3">
                <h4 class="text-5xl  dark:text-white font-[200]">La casa de papel</h4>
                <p class="text-gray-500 font-[230] dark:text-gray-400 max-w-xl mx-auto">Lorem, ipsum dolor sit amet
                    consectetur adipisicing elit. Perferendis recusandae, tempora nisi consectetur autem explicabo
                    reprehenderit et nesciunt repellendus distinctio similique soluta voluptatem veritatis quia ratione
                    libero tempore expedita eos?</p>
            </div>


            <img class=" lg:min-h-[calc(100vh_+_5.9rem)] w-full px-6"
                src="https://images.unsplash.com/photo-1595432217185-43c5c403a81a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80"
                alt="image">

        </section>

        <br><br>

        <footer class=" mt-auto     dark:bg-gray-800 dark:text-white bg-white">
            <div class="max-w-screen-xl px-4 container py-4 mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <div>
                        <img class="object-cover h-9 w-9 rounded-full"
                            src="https://images.unsplash.com/photo-1516876437184-593fda40c7ce?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2672&q=80"alt="logo" />
                        <p class="max-w-xs mt-4 text-xs text-gray-600  dark:text-white">
                            Here at 'BrandName' we take care of all the hard part so you can only focus on the results
                        </p>
                        <div class="flex mt-8 space-x-6 text-gray-600 dark:text-gray-200">
                            <a class="hover:opacity-75 cursor-pointer">
                                <span class="sr-only"> Facebook </span>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fillRule="evenodd"
                                        d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                        clipRule="evenodd" />
                                </svg>
                            </a>
                            <a class="hover:opacity-75 cursor-pointer">
                                <span class="sr-only"> Instagram </span>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fillRule="evenodd"
                                        d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                        clipRule="evenodd" />
                                </svg>
                            </a>
                            <a class="hover:opacity-75 cursor-pointer">
                                <span class="sr-only"> Twitter </span>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path
                                        d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                </svg>
                            </a>
                            <a class="hover:opacity-75 cursor-pointer">
                                <span class="sr-only"> GitHub </span>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fillRule="evenodd"
                                        d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                        clipRule="evenodd" />
                                </svg>
                            </a>
                            <a class="hover:opacity-75 cursor-pointer">
                                <span class="sr-only"> Dribbble </span>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fillRule="evenodd"
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm6.605 4.61a8.502 8.502 0 011.93 5.314c-.281-.054-3.101-.629-5.943-.271-.065-.141-.12-.293-.184-.445a25.416 25.416 0 00-.564-1.236c3.145-1.28 4.577-3.124 4.761-3.362zM12 3.475c2.17 0 4.154.813 5.662 2.148-.152.216-1.443 1.941-4.48 3.08-1.399-2.57-2.95-4.675-3.189-5A8.687 8.687 0 0112 3.475zm-3.633.803a53.896 53.896 0 013.167 4.935c-3.992 1.063-7.517 1.04-7.896 1.04a8.581 8.581 0 014.729-5.975zM3.453 12.01v-.26c.37.01 4.512.065 8.775-1.215.25.477.477.965.694 1.453-.109.033-.228.065-.336.098-4.404 1.42-6.747 5.303-6.942 5.629a8.522 8.522 0 01-2.19-5.705zM12 20.547a8.482 8.482 0 01-5.239-1.8c.152-.315 1.888-3.656 6.703-5.337.022-.01.033-.01.054-.022a35.318 35.318 0 011.823 6.475 8.4 8.4 0 01-3.341.684zm4.761-1.465c-.086-.52-.542-3.015-1.659-6.084 2.679-.423 5.022.271 5.314.369a8.468 8.468 0 01-3.655 5.715z"
                                        clipRule="evenodd" />
                                </svg>
                            </a>
                        </div>

                    </div>
                    <div class="lg:col-span-2 grid grid-cols-2 gap-8  sm:grid-cols-4  ">
                        <div>
                            <p
                                class="font-semibold dark:text-gray-400 text-gray-500 uppercase dark:text-gray-400 text-sm">
                                Company
                            </p>
                            <nav class="flex flex-col mt-4 space-y-2  text-gray-500 dark:text-gray-400 text-xs">
                                <a class="hover:opacity-75 cursor-pointer"> About </a>
                                <a class="hover:opacity-75 cursor-pointer"> Meet the Team </a>
                                <a class="hover:opacity-75 cursor-pointer"> History </a>
                                <a class="hover:opacity-75 cursor-pointer"> Our mission </a>
                            </nav>
                        </div>
                        <div>
                            <p
                                class="font-semibold dark:text-gray-400 text-gray-500 uppercase dark:text-gray-400 text-sm">
                                Services
                            </p>
                            <nav class="flex flex-col mt-4 space-y-2  text-gray-500 dark:text-gray-400 text-xs">
                                <a class="hover:opacity-75 cursor-pointer"> service1 </a>
                                <a class="hover:opacity-75 cursor-pointer"> service2 </a>
                                <a class="hover:opacity-75 cursor-pointer"> service3 </a>
                                <a class="hover:opacity-75 cursor-pointer"> service4 </a>

                            </nav>
                        </div>
                        <div>
                            <p
                                class="font-semibold dark:text-gray-400 text-gray-500 uppercase dark:text-gray-400 text-sm">
                                Helpful Links
                            </p>
                            <nav class="flex flex-col mt-4 space-y-2  text-gray-500 dark:text-gray-400 text-xs">
                                <a class="hover:opacity-75 cursor-pointer"> Contact </a>
                                <a class="hover:opacity-75 cursor-pointer"> FAQs </a>
                                <a class="hover:opacity-75 cursor-pointer"> Live Chat </a>
                            </nav>
                        </div>
                        <div>
                            <p
                                class="font-semibold dark:text-gray-400 text-gray-500 uppercase dark:text-gray-400 text-sm">
                                App
                            </p>
                            <nav class="flex flex-col mt-4 space-y-2  text-gray-500 dark:text-gray-400 text-xs">
                                <a class="hover:opacity-75 cursor-pointer"> Privacy Policy </a>
                                <a class="hover:opacity-75 cursor-pointer"> Terms &amp; Conditions </a>
                                <a class="hover:opacity-75 cursor-pointer"> check out </a>
                                <a class="hover:opacity-75 cursor-pointer"> Accessibility </a>
                            </nav>
                        </div>
                    </div>
                </div>


            </div>
            <center>
                <p class="mt-5 text-xs p-5 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                    © 2022 Brand
                </p>
            </center>
        </footer>



        <!-- code end -->
    </section>
</div>


{{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
<script src="https://cdn.tailwindcss.com"></script>
