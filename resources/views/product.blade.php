@extends('master')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- start main -->
    <main role="main" class="mt-12">
        <!-- Common styles
        ================================================== -->
        <link rel="stylesheet" href="/css/style.min.css" type="text/css">

        <!-- Load lazyLoad scripts
        ================================================== -->
        <script>
            (function(w, d){
                var m = d.getElementsByTagName('main')[0],
                    s = d.createElement("script"),
                    v = !("IntersectionObserver" in w) ? "8.17.0" : "10.19.0",
                    o = {
                        elements_selector: ".lazy",
                        data_src: 'src',
                        data_srcset: 'srcset',
                        threshold: 500,
                        callback_enter: function (element) {

                        },
                        callback_load: function (element) {
                            element.removeAttribute('data-src')

                            oTimeout = setTimeout(function ()
                            {
                                clearTimeout(oTimeout);

                                AOS.refresh();
                            }, 1000);
                        },
                        callback_set: function (element) {

                        },
                        callback_error: function(element) {
                            element.src = "https://placeholdit.imgix.net/~text?txtsize=21&amp;txt=Image%20not%20load&amp;w=200&amp;h=200";
                        }
                    };
                s.type = 'text/javascript';
                s.async = true; // This includes the script as async. See the "recipes" section for more information about async loading of LazyLoad.
                s.src = "https://cdn.jsdelivr.net/npm/vanilla-lazyload@" + v + "/dist/lazyload.min.js";
                m.appendChild(s);
                // m.insertBefore(s, m.firstChild);
                w.lazyLoadOptions = o;
            }(window, document));
        </script>

        <!-- start section -->
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-12">

                        <!-- start product single -->
                        <div class="product-single">
                            <div class="row">
                                <div class="col-12 col-lg-7">
                                    <div class="__product-img">
                                        <img width="330" src="{{ Storage::url($product->_get('cover')) }}" alt="demo" />

                                        <span class="product-label product-label--new">{{ __('Yeni') }}</span>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-5">
                                    <div class="content-container">
                                        <h3 class="__name">{{ $product->_get('title') }}</h3>

                                        <div class="product-price">
                                            <span class="product-price__item product-price__item--new">{{ $product->_get('price') }}₺</span>
                                        </div>

                                        {!! $product->_get('content') !!}

                                        <form class="__add-to-cart" action="#">
                                            <div class="quantity-counter js-quantity-counter">
                                                <span class="__btn __btn--minus"></span>
                                                <input class="__q-input" type="text" value="1" />
                                                <span class="__btn __btn--plus"></span>
                                            </div>

                                            <button class="custom-btn custom-btn--medium custom-btn--style-1" type="submit" id="addChart" role="button">
                                                <i class="fontello-shopping-bag"></i>{{ __('Sepete Ekle') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <script>
                                    document.getElementById('addChart').addEventListener('click', function (event) {
                                        event.preventDefault();
                                        Swal.fire({
                                            title: 'Hata!',
                                            text: 'Ürünü sepete eklemek için lütfen giriş yapınız',
                                            icon: 'error',
                                            confirmButtonText: 'Tamam'
                                        })
                                    })
                                </script>

                                <div class="col-12">
                                    <div class="spacer py-5 py-md-9"></div>

                                    <!-- start tab -->
                                    <div class="tab-container">
                                        <nav class="tab-nav">
                                            <a href="#">{{ __('Açıklama') }}</a>
                                        </nav>

                                        <div class="tab-content">
                                            <div class="tab-content__item is-visible">
                                                <p>
                                                    The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic words etc. Susp endisse ultricies nisi vel quam suscipit
                                                </p>

                                                <p>
                                                    Sabertooth peacock flounder; chain pickerel hatchetfish, pencilfish snailfish filefish Antarctic icefish goldeye aholehole trumpetfish pilot fish airbreathing catfish, electric ray sweeper.
                                                </p>

                                                <div class="description-table" style="max-width: 370px;">
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td>Ağırlık</td>
                                                            <td>1 kg</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kalite</td>
                                                            <td>Organic</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kontrol</td>
                                                            <td>Sağlıklı</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end tab -->

                                    <div class="spacer py-5 py-md-9"></div>
                                </div>
                            </div>
                        </div>
                        <!-- start product single -->

                    </div>

                </div>
            </div>
        </section>
        <!-- end section -->

        <!-- start section -->
        <section class="section section--no-pt section--no-pb section--gutter">
            <div class="container-fluid px-md-0">
                <!-- start banner simple -->
                <div class="simple-banner simple-banner--style-2" data-aos="fade" data-aos-offset="50">
                    <div class="d-none d-lg-block">
                        <img class="img-logo img-fluid lazy" style="margin-top: -10px;" src="/img/blank.gif" width="40" data-src="{{ Storage::url($settings->_get('logo')) }}" alt="demo" />
                    </div>

                    <div class="row no-gutters">
                        <div class="col-12 col-lg-6">
                            <a href="#"><img class="img-fluid w-100  lazy" src="/img/blank.gif" data-src="/banner_bg_3.jpg" alt="demo" /></a>
                        </div>

                        <div class="col-12 col-lg-6">
                            <a href="#"><img class="img-fluid w-100  lazy" src="/img/blank.gif" data-src="/banner_bg_4.jpg" alt="demo" /></a>
                        </div>
                    </div>
                </div>
                <!-- end banner simple -->
            </div>
        </section>
        <!-- end section -->
    </main>
@endsection
