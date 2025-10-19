<section class="relative py-20 md:py-32 overflow-hidden">
    <!-- Background gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-accent/5 pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center space-y-8">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-muted rounded-full border border-border">
                <span class="w-2 h-2 bg-accent rounded-full"></span>
                <span class="text-sm font-medium text-muted-foreground">Now with AI-powered insights</span>
            </div>

            <!-- Headline -->
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight text-balance">
                Smart Expense Management
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-primary to-accent">
                    Made Simple
                </span>
            </h1>

            <!-- Subheading -->
            <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto text-balance leading-relaxed">
                Take control of your finances with our intuitive expense tracking platform. Monitor spending, set budgets,
                and achieve your financial goals effortlessly.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-primary text-primary-foreground rounded-lg font-semibold hover:opacity-90 transition-opacity">
                    Get Started Free
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="#features" class="inline-flex items-center justify-center px-8 py-4 border-2 border-primary text-primary rounded-lg font-semibold hover:bg-primary/5 transition-colors">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>
