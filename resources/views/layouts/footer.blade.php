<footer class="footer footer-black  footer-white">
    <div class="{{ $containerCss ?? 'container' }} text-black">
        <div class="row align-items-end">
            <div class="col-8">
                <div>
                    Location: <i>KM29 National Highway Cagangohan, Panabo, Philippines</i>
                </div>
                <span class="me-2">
                    Contact Number: <a href="tel:0917 713 0990" class="text-black"><i>0917 713 0990</i></a>
                </span>
                <span>
                    Email: <a href="mailto:innovaplaspackagingcorpo@gmail.com" class="text-black"><i>innovaplaspackagingcorpo@gmail.com</i></a>
                </span>
            </div>
            <div class="col-4">
                <div class="credits ml-auto">
                    <span class="copyright">
                        {{ config('app.name') }} © {{ now()->format('Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>