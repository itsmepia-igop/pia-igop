@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@section('content')
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-12">
                    <h5>Terms and Conditions</h5>
                    <p>Welcome to <b>Innovaplas Packaging Corporation</b>! By accessing or using our services, you agree to comply with and be bound by these Terms and Conditions. Please read them carefully before proceeding.</p>
                    <h6>1. General Terms</h6>
                    <div class="ms-3">
                        <p>1.1 These Terms and Conditions govern your use of Innovaplas Packaging Corporation’s website, products, and services.</p>
                        <p>1.2 We reserve the right to update or change these terms at any time without prior notice.</p>
                    </div>
                    <h6>2. User Responsibilities</h6>
                    <div class="ms-3">
                        <p>2.1 You agree to provide accurate and up-to-date information during registration or checkout.</p>
                        <p>2.2 Unauthorized use of our platform is prohibited and may result in suspension or termination of your account.</p>
                        <p>2.3 You are responsible for maintaining the confidentiality of your account details.</p>
                    </div>
                    <h6>3. Payment and Billing</h6>
                    <div class="ms-3">
                        <p>3.1 All prices are displayed in <b>Peso (₱)</b> and include applicable taxes unless stated otherwise.</p>
                        <p>3.2 Payment must be completed using approved payment methods listed on our platform.</p>
                        <p>3.3 In case of failed transactions or disputes, please contact our support team at <a href="mailto:innovaplaspackagingcorpo@gmail.com">innovaplaspackagingcorpo@gmail.com</a>.</p>
                    </div>
                    <h6>4. Cancellation and Refunds</h6>
                    <div class="ms-3">
                        <p>4.1 Orders can be canceled only under the conditions outlined in our <b>Cancellation Policy</b>.</p>
                        <p>4.2 Refunds are processed according to our <b>Refund Policy</b> and may take up to [X] business days.</p>
                        <p>4.3 Non-refundable items or services will be clearly marked during the purchase process.</p>
                    </div>
                    <h6>5. Intellectual Property</h6>
                    <div class="ms-3">
                        <p>5.1 All content, logos, and trademarks displayed on our website are the property of <b>Innovaplas Packaging Corporation</b>.</p>
                        <p>5.2 Unauthorized reproduction, distribution, or modification of our content is strictly prohibited.</p>
                    </div>
                    <h6>6. Limitation of Liability</h6>
                    <div class="ms-3">
                        <p>6.1 Innovaplas Packaging Corporation is not responsible for any indirect, incidental, or consequential damages arising from the use of our services.</p>
                        <p>6.2 We are not liable for delays, errors, or interruptions caused by external factors beyond our control.</p>
                    </div>
                    <h6>7. Privacy</h6>
                    <div class="ms-3">
                        <p>7.1 Your use of our services is subject to our <b>Privacy Policy</b>, which outlines how we collect, store, and process your data.</p>
                    </div>
                    <h6>8. Contact Information</h6>
                    <div class="ms-3">
                        <p>For any questions regarding these Terms and Conditions, please contact us:</p>
                        <p>Email: <a href="mailto:innovaplaspackagingcorpo@gmail.com">innovaplaspackagingcorpo@gmail.com</a></p>
                        <p>Phone: <a href="tel:0917 713 0990">0917 713 0990</a></p>
                    </div>
                </div>
            </div>
        </div>
     </div> 
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            demo.checkFullPageBackgroundImage();
        });
    </script>
@endpush
