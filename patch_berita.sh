sed -i '88,152c\
                <!-- HERO -->\
                <div x-show="hero" class="mb-12">\
                    <a :href="hero?.url" class="group block md:grid md:grid-cols-2 gap-6 lg:gap-10 items-center">\
                        <div class="w-full aspect-[4/3] lg:aspect-[16/9] rounded-2xl overflow-hidden mb-5 md:mb-0 bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5">\
                            <template x-if="hero?.image">\
                                <img :src="hero.image" :alt="hero.title" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">\
                            </template>\
                            <template x-if="!hero?.image">\
                                <div class="w-full h-full flex items-center justify-center text-emerald-600/30 font-serif text-4xl font-medium" x-text="hero?.title?.substring(0,2)"></div>\
                            </template>\
                        </div>\
                        <div class="flex flex-col justify-center">\
                            <div class="flex items-center gap-2 mb-3 text-[10px] font-medium uppercase tracking-widest" style="color:var(--text-muted)">\
                                <span x-text="hero?.category"></span>\
                                <span>·</span>\
                                <span x-text="hero?.date"></span>\
                            </div>\
                            <h2 class="font-serif text-3xl lg:text-4xl font-medium leading-tight mb-4 group-hover:text-emerald-600 transition-colors line-clamp-3" style="color:var(--text-primary)" x-text="hero?.title"></h2>\
                            <p class="text-base leading-relaxed line-clamp-3" style="color:var(--text-secondary)" x-text="hero?.full_ex"></p>\
                        </div>\
                    </a>\
                </div>\
\
                <!-- COMPACT LIST -->\
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12" x-show="restItems.length > 0">\
                    <template x-for="item in restItems" :key="item.id">\
                        <a :href="item.url" class="flex items-center gap-4 group p-3 rounded-2xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors border border-transparent hover:border-black/5 dark:hover:border-white/5">\
                            <div class="w-24 md:w-28 aspect-square md:aspect-[4/3] shrink-0 rounded-xl overflow-hidden bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5">\
                                <template x-if="item.image">\
                                    <img :src="item.image" :alt="item.title" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">\
                                </template>\
                                <template x-if="!item.image">\
                                    <div class="w-full h-full flex items-center justify-center text-emerald-600/30 font-serif text-xl font-medium" x-text="item.title.substring(0,2)"></div>\
                                </template>\
                            </div>\
                            <div class="flex flex-col py-1 justify-center flex-grow">\
                                <span class="text-[10px] font-medium uppercase tracking-widest mb-1.5" style="color:var(--text-muted)" x-text="item.date"></span>\
                                <h3 class="font-serif text-base md:text-lg font-medium leading-snug group-hover:text-emerald-600 transition-colors line-clamp-2" style="color:var(--text-primary)" x-text="item.title"></h3>\
                            </div>\
                        </a>\
                    </template>\
                </div>\
\
                <!-- LOAD MORE -->\
                <div class="text-center pb-8" x-show="hasMore">\
                    <button @click="loadMore" class="px-6 py-2.5 btn-active-scale rounded-xl text-sm font-medium transition-colors bg-black/5 dark:bg-white/5 hover:bg-black/10 dark:hover:bg-white/10" style="color:var(--text-primary)">\
                        Muat lebih banyak\
                    </button>\
                </div>\
' resources/views/berita/index.blade.php
bash patch_berita.sh
