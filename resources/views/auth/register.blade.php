<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký</title>

    {{-- Liên kết đến file CSS nằm trong thư mục public/css --}}
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}" />

</head>

<body>


    @include('layouts.header')

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container_header">
            <span> <a href="{{asset('/home')}}">TRANG CHỦ</a> </span>
            <span class="separator">/</span>
            <span>ĐĂNG KÝ TÀI KHOẢN THÀNH VIÊN</span>
        </div>
    </div>


    <div class="form-container">
        <div class="form-wrapper">
            <h2>Đăng ký tài khoản</h2>

            {{-- Hiển thị lỗi nếu có --}}
            @if ($errors->any())
            <div class=" error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li style="color: red;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Hiển thị thông báo thành công nếu có --}}
            @if(session('success'))
            <div class="success-message" style="color: green;">
                {{ session('success') }}
            </div>
            @endif

            {{-- Form đăng ký --}}
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email đăng ký*</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        required />
                </div>

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Tên</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-input" />
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label for="phone" class="form-label">Số điện thoại*</label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        class="form-input"
                        required />
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label class="form-label">Giới tính</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input
                                type="radio"
                                name="gender"
                                value="nam"
                                class="radio-input" />
                            <span class="radio-text">Nam</span>
                        </label>
                        <label class="radio-label">
                            <input
                                type="radio"
                                name="gender"
                                value="nu"
                                class="radio-input" />
                            <span class="radio-text">Nữ</span>
                        </label>
                    </div>
                </div>

                <!-- Date of Birth -->
                <div class="form-group">
                    <label class="form-label">Ngày sinh</label>
                    <div class="date-group">
                        <select name="day" class="date-select">
                            <option value="">- Ngày -</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                        </select>
                        <select name="month" class="date-select">
                            <option value="">- Tháng -</option>
                            <option value="1">Tháng 1</option>
                            <option value="2">Tháng 2</option>
                            <option value="3">Tháng 3</option>
                            <option value="4">Tháng 4</option>
                            <option value="5">Tháng 5</option>
                            <option value="6">Tháng 6</option>
                            <option value="7">Tháng 7</option>
                            <option value="8">Tháng 8</option>
                            <option value="9">Tháng 9</option>
                            <option value="10">Tháng 10</option>
                            <option value="11">Tháng 11</option>
                            <option value="12">Tháng 12</option>
                        </select>
                        <select name="year" class="date-select">
                            <option value="">Năm</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                            <option value="2016">2016</option>
                            <option value="2015">2015</option>
                            <option value="2014">2014</option>
                            <option value="2013">2013</option>
                            <option value="2012">2012</option>
                            <option value="2011">2011</option>
                            <option value="2010">2010</option>
                            <option value="2009">2009</option>
                            <option value="2008">2008</option>
                            <option value="2007">2007</option>
                            <option value="2006">2006</option>
                            <option value="2005">2005</option>
                            <option value="2004">2004</option>
                            <option value="2003">2003</option>
                            <option value="2002">2002</option>
                            <option value="2001">2001</option>
                            <option value="2000">2000</option>
                            <option value="1999">1999</option>
                            <option value="1998">1998</option>
                            <option value="1997">1997</option>
                            <option value="1996">1996</option>
                            <option value="1995">1995</option>
                            <option value="1994">1994</option>
                            <option value="1993">1993</option>
                            <option value="1992">1992</option>
                            <option value="1991">1991</option>
                            <option value="1990">1990</option>
                        </select>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu*</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        required />
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="confirm-password" class="form-label">Nhập lại mật khẩu*</label>
                    <input
                        type="password"
                        id="confirm-password"
                        name="password_confirmation"
                        class="form-input"
                        required />
                </div>


                <!-- Address -->
                <div class="form-group">
                    <label for="address" class="form-label">Địa chỉ*</label>
                    <input
                        type="text"
                        id="address"
                        name="address"
                        class="form-input"
                        required />
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="submit-btn">
                        ĐĂNG KÝ
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>