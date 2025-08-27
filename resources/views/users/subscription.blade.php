@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Subscription Plan'])

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
                <section class="subscription-section">
                    <div class="subscription-container">
                        <h2 class="subscription-title">Subscription Plans for Students</h2>
                        <p class="subscription-subtitle">Choose an affordable plan with the best features to boost your
                            learning.</p>

                        <div class="subscription-grid">

                            <!-- Basic -->
                            <div class="plan-card basic">
                                <h3>1 Month <span>Basic</span></h3>
                                <p class="price">$59</p>
                                <ul class="features">
                                    <li>✔ CCL Practice Dialogues</li>
                                    <li>✔ Recent VIP Exam Material</li>
                                    <li>✔ Full Length Vocabulary Access</li>
                                    <li>✔ 3 Mock Test</li>
                                    <li>✔ Full Video Access</li>
                                    <li>✔ User Access To The Answers</li>
                                    <li class="not-included">✘ Expert Feedback & Scorecard</li>
                                </ul>
                                <button class="choose-btn">Choose Plan</button>
                            </div>

                            <!-- Intermediate -->
                            <div class="plan-card intermediate popular">
                                <span class="most-popular-badge">Most Popular</span>
                                <h3>2 Months <span>Intermediate</span></h3>
                                <p class="price">$99</p>
                                <ul class="features">
                                    <li>✔ CCL Practice Dialogues</li>
                                    <li>✔ Recent VIP Exam Material</li>
                                    <li>✔ Full Length Vocabulary Access</li>
                                    <li>✔ 5 Mock Test</li>
                                    <li>✔ Full Video Access</li>
                                    <li>✔ User Access To The Answers</li>
                                    <li>✔ 1 Mock Test With Expert Feedback</li>
                                </ul>
                                <button class="choose-btn">Choose Plan</button>
                            </div>

                            <!-- Advance -->
                            <div class="plan-card advance">
                                <h3>2 Months <span>Advance</span></h3>
                                <p class="price">$149</p>
                                <ul class="features">
                                    <li>✔ CCL Practice Dialogues</li>
                                    <li>✔ Recent VIP Exam Material</li>
                                    <li>✔ Full Length Vocabulary Access</li>
                                    <li>✔ 10 Mock Test</li>
                                    <li>✔ Full Video Access</li>
                                    <li>✔ User Access To The Answers</li>
                                    <li>✔ 2 Mock Tests With Expert Feedback</li>
                                </ul>
                                <button class="choose-btn">Choose Plan</button>
                            </div>

                        </div>
                    </div>
                </section>

                <section class="terms-box">
                    <h3 class="terms-title">⚖ Terms and Conditions</h3>
                    <ul class="terms-list">
                        <li>Once the payment is made, no refund will be issued. If you cancel the course in the middle of
                            the course, still no refund will be issued, but your course will continue till the period
                            lapses.</li>
                        <li>All the courses are strictly non-transferable.</li>
                        <li>Top-ups can be used, but the course expiry still stays the same as per the initial plan.</li>
                    </ul>
                </section>
            </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
