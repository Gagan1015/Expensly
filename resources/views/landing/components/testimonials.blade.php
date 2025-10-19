<section id="testimonials" class="py-20 md:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16 space-y-4">
            <h2 class="text-4xl md:text-5xl font-bold text-balance">Loved by Thousands of Users</h2>
            <p class="text-lg text-muted-foreground max-w-2xl mx-auto text-balance">
                Join thousands of satisfied users managing their finances better
            </p>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="p-8 rounded-xl bg-card border border-border hover:border-primary/50 hover:shadow-lg transition-all duration-300">
                <div class="flex gap-1 mb-6">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 fill-accent text-accent" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    @endfor
                </div>
                <p class="text-muted-foreground mb-6 leading-relaxed">"Expensly has transformed how I manage my business expenses. The categorization and reporting features are incredible!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-primary-foreground font-semibold text-sm">
                        JD
                    </div>
                    <div>
                        <div class="font-semibold text-sm">John Doe</div>
                        <div class="text-xs text-muted-foreground">Small Business Owner</div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="p-8 rounded-xl bg-card border border-border hover:border-primary/50 hover:shadow-lg transition-all duration-300">
                <div class="flex gap-1 mb-6">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 fill-accent text-accent" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    @endfor
                </div>
                <p class="text-muted-foreground mb-6 leading-relaxed">"Finally, an expense tracker that's both powerful and easy to use. I've saved hours on my monthly bookkeeping!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-primary-foreground font-semibold text-sm">
                        SM
                    </div>
                    <div>
                        <div class="font-semibold text-sm">Sarah Miller</div>
                        <div class="text-xs text-muted-foreground">Freelancer</div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="p-8 rounded-xl bg-card border border-border hover:border-primary/50 hover:shadow-lg transition-all duration-300">
                <div class="flex gap-1 mb-6">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 fill-accent text-accent" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    @endfor
                </div>
                <p class="text-muted-foreground mb-6 leading-relaxed">"The budget alerts have helped our family stay on track financially. We've never been more organized with our expenses."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-primary-foreground font-semibold text-sm">
                        MJ
                    </div>
                    <div>
                        <div class="font-semibold text-sm">Mike Johnson</div>
                        <div class="text-xs text-muted-foreground">Family Budget Manager</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
