<section class="py-20 md:py-32 bg-muted/30">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
        <h2 class="text-4xl md:text-5xl font-bold text-balance">Ready to Take Control of Your Finances?</h2>
        <p class="text-lg text-muted-foreground max-w-2xl mx-auto text-balance">
            Join thousands of users who have already transformed their financial management with Expensly. Start your free
            trial today.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-primary text-primary-foreground rounded-lg font-semibold hover:opacity-90 transition-opacity">
                Start Free Trial
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-primary text-primary rounded-lg font-semibold hover:bg-primary/5 transition-colors">
                Sign In
            </a>
        </div>
    </div>
</section>
