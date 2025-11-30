<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket - {{ $booking->booking_code }}</title>
    <style>
        @page {
            margin: 20px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            background: #ffffff;
            font-size: 12px;
        }

        .ticket {
            background: #006ACD;
            border-radius: 16px;
            padding: 15px;
        }

        .ticket-inner {
            background: #111111;
            border-radius: 12px;
            padding: 25px;
            color: #ffffff;
        }

        .event-name {
            font-size: 22px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-table td {
            vertical-align: top;
            padding: 5px;
        }

        .info-box {
            background: #222222;
            border: 1px solid #333333;
            border-radius: 8px;
            padding: 10px 15px;
            text-align: center;
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 10px;
        }

        .info-label {
            font-size: 9px;
            color: #888888;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
        }

        .datetime-box {
            background: #222222;
            border: 1px solid #333333;
            border-radius: 8px;
            padding: 10px 15px;
            display: inline-block;
        }

        .date-text {
            font-size: 10px;
            color: #888888;
            text-transform: uppercase;
        }

        .time-text {
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            margin-top: 3px;
        }

        .ticket-details {
            background: #222222;
            border: 1px solid #333333;
            border-radius: 8px;
            padding: 12px 15px;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .ticket-details-title {
            font-size: 9px;
            color: #888888;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .ticket-item {
            padding: 5px 0;
            border-bottom: 1px solid #333333;
        }

        .ticket-item:last-child {
            border-bottom: none;
        }

        .ticket-item-name {
            font-size: 12px;
            color: #ffffff;
        }

        .ticket-item-qty {
            font-size: 12px;
            color: #888888;
        }

        .venue-box {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #333333;
        }

        .venue-label {
            font-size: 9px;
            color: #888888;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .venue-value {
            font-size: 13px;
            color: #ffffff;
        }

        .venue-location {
            font-size: 11px;
            color: #aaaaaa;
            margin-top: 3px;
        }

        .qr-box {
            background: #ffffff;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            width: 140px;
        }

        .qr-placeholder {
            width: 90px;
            height: 90px;
            background: #000000;
            margin: 0 auto 10px auto;
            border: 2px solid #333333;
            text-align: center;
            line-height: 90px;
            color: #ffffff;
            font-size: 8px;
        }

        .qr-text {
            font-size: 8px;
            color: #333333;
            font-weight: bold;
            word-break: break-all;
        }

        .divider {
            border-top: 2px dashed #333333;
            margin: 20px 0;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            padding: 5px 0;
        }

        .footer-label {
            font-size: 9px;
            color: #666666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-value {
            font-size: 12px;
            color: #ffffff;
            margin-top: 3px;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    @php
        $totalTickets = $booking->details->sum('quantity');
    @endphp

    <div class="ticket">
        <div class="ticket-inner">
            <!-- Header -->
            <div class="event-name">{{ $event->name }}</div>

            <!-- Main Content -->
            <table class="content-table">
                <tr>
                    <td style="width: 70%;">
                        <!-- Info Boxes -->
                        <div class="info-box">
                            <div class="info-label">Code</div>
                            <div class="info-value">{{ substr($booking->booking_code, -6) }}</div>
                        </div>

                        <div class="info-box">
                            <div class="info-label">Tickets</div>
                            <div class="info-value">{{ $totalTickets }}</div>
                        </div>

                        <div class="datetime-box">
                            <div class="date-text">{{ $event->date_start->format('d F Y') }}</div>
                            <div class="time-text">{{ date('H:i', strtotime($event->time_start)) }} WIB</div>
                        </div>

                        <!-- Ticket Details -->
                        <div class="ticket-details">
                            <div class="ticket-details-title">Ticket Details</div>
                            @foreach($booking->details as $detail)
                                <table width="100%" class="ticket-item">
                                    <tr>
                                        <td class="ticket-item-name">{{ $detail->ticket->name }}</td>
                                        <td class="ticket-item-qty" style="text-align: right;">x{{ $detail->quantity }}</td>
                                    </tr>
                                </table>
                            @endforeach
                        </div>

                        <!-- Venue -->
                        <div class="venue-box">
                            <div class="venue-label">Venue</div>
                            <div class="venue-value">{{ $event->venue }}</div>
                            <div class="venue-location">{{ $event->location }}</div>
                        </div>
                    </td>
                    <td style="width: 30%; text-align: right;">
                        <!-- QR Section -->
                        <div class="qr-box">
                            <div class="qr-placeholder">
                                SCAN QR
                            </div>
                            <div class="qr-text">{{ $booking->booking_code }}</div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Divider -->
            <div class="divider"></div>

            <!-- Footer -->
            <table class="footer-table">
                <tr>
                    <td style="width: 35%;">
                        <div class="footer-label">Attendee Name</div>
                        <div class="footer-value">{{ $booking->user->name }}</div>
                    </td>
                    <td style="width: 40%;">
                        <div class="footer-label">Email</div>
                        <div class="footer-value">{{ $booking->user->email }}</div>
                    </td>
                    <td style="width: 25%;" class="text-right">
                        <div class="footer-label">Total</div>
                        <div class="footer-value">{{ $booking->formatted_total }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>