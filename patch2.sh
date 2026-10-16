sed -i '168,213c\
                @else\
                    @php $featured = $beritas->first(); @endphp\
\
                    <!-- HERO -->\
                    <div class="mb-12 reveal-up">\
                        <a href="{{ route('\''berita.show'\'', $featured->id) }}" class="group block md:grid md:grid-cols-2 gap-6 lg:gap-10 items-center">\
                            <div class="w-full aspect-[4/3] lg:aspect-[16/9] overflow-hidden rounded-2xl mb-5 md:mb-0 bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5">\
                                @if($featured->image)\
                                    <img src="{{ asset('\''storage/'\'' . $featured->image) }}" alt="{{ $featured->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">\
                                @else\
                                    <div class="w-full h-full flex items-center justify-center text-emerald-600/30 text-4xl font-serif font-medium">\
                                        {{ substr($featured->title, 0, 2) }}\
                                    </div>\
                                @endif\
                            </div>\
                            <div class="flex flex-col justify-center">\
                                <div class="flex items-center gap-2 mb-3 text-[10px] font-medium uppercase tracking-widest" style="color: var(--text-muted)">\
                                    <span>{{ $featured->category ?? '\''Warta'\'' }}</span>\
                                    <span>·</span>\
                                    <span>@tanggal($featured->created_at)</span>\
                                </div>\
                                <h3 class="font-serif text-3xl lg:text-4xl font-medium mb-4 leading-tight group-hover:text-emerald-600 transition-colors line-clamp-3" style="color: var(--text-primary)">\
                                    {{ $featured->title }}\
                                </h3>\
                                <p class="text-base leading-relaxed line-clamp-3" style="color: var(--text-secondary)">\
                                    {{ Str::limit(strip_tags($featured->content), 220) }}\
                                </p>\
                            </div>\
                        </a>\
                    </div>\
\
                    <!-- COMPACT LIST -->\
                    @if($beritas->count() > 1)\
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12 reveal-up" style="transition-delay: 100ms">\
                        @foreach($beritas->skip(1) as $berita)\
                        <a href="{{ route('\''berita.show'\'', $berita->id) }}" class="flex items-center gap-4 group p-3 rounded-2xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors border border-transparent hover:border-black/5 dark:hover:border-white/5">\
                            <div class="w-24 md:w-28 aspect-square md:aspect-[4/3] shrink-0 rounded-xl overflow-hidden bg-emerald-50 dark:bg-emerald-900/10 border border-black/5 dark:border-white/5">\
                                @if($berita->image)\
                                    <img src="{{ asset('\''storage/'\'' . $berita->image) }}" alt="{{ $berita->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">\
                                @else\
                                    <div class="w-full h-full flex items-center justify-center text-emerald-600/30 text-xl font-serif font-medium">\
                                        {{ substr($berita->title, 0, 2) }}\
                                    </div>\
                                @endif\
                            </div>\
                            <div class="flex flex-col py-1 justify-center flex-grow">\
                                <span class="text-[10px] font-medium uppercase tracking-widest mb-1.5" style="color: var(--text-muted)">\
                                    @tanggal($berita->created_at)\
                                </span>\
                                <h4 class="font-serif text-base md:text-lg font-medium leading-snug group-hover:text-emerald-600 transition-colors line-clamp-2" style="color: var(--text-primary)">\
                                    {{ $berita->title }}\
                                </h4>\
                            </div>\
                        </a>\
                        @endforeach\
                    </div>\
                    @endif\
' resources/views/welcome.blade.php
bash patch2.sh
