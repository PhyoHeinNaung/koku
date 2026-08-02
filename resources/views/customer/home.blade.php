<x-app-layout :overlay="true">

    {{-- Hero --}}
    <section class="relative h-screen flex items-center overflow-hidden">
        <img src="https://i.pinimg.com/1200x/9a/b1/2c/9ab12cc297b2da1203cf11e0e9d7012e.jpg"
            alt="Featured watch collection" class="absolute inset-0 w-full h-full object-cover">


        <div class="absolute inset-0 bg-black/30"></div>

        <div class="relative z-10 px-6 sm:px-10 lg:px-16 w-full">
            <div class="max-w-xl">
                <h1 class="text-5xl sm:text-6xl font-bold leading-tight text-white mb-6">
                    Every second, <br> considered.
                </h1>
                {{-- <p class="text-lg text-white/80 mb-8">
                    Discover curated watch collections <br>from the world's most trusted brands.
                </p> --}}
                <a href="#" class="btn border-none bg-white text-neutral-900 hover:bg-white/90">
                    Shop Now
                </a>
            </div>
        </div>
    </section>

    <div class="hero bg-base-200 min-h-screen">
        <div class="hero-content flex-col lg:flex-row-reverse">
            <div class="text-center lg:text-left">
                <h1 class="text-5xl font-bold">Login now!</h1>
                <p class="py-6">
                    Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem
                    quasi. In deleniti eaque aut repudiandae et a id nisi.
                </p>
            </div>
            <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
                <div class="card-body">
                    <fieldset class="fieldset">
                        <label class="label">Email</label>
                        <input type="email" class="input" placeholder="Email" />
                        <label class="label">Password</label>
                        <input type="password" class="input" placeholder="Password" />
                        <div><a class="link link-hover">Forgot password?</a></div>
                        <button class="btn btn-neutral mt-4">Login</button>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>



</x-app-layout>