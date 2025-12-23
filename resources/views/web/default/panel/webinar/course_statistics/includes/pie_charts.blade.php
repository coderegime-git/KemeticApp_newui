<style>
    /* ===========================
   KEMETIC STATISTIC CARD
=========================== */

.kemetic-stat-card {
    background: linear-gradient(180deg, #161616, #101010);
    border: 1px solid rgba(242, 201, 76, 0.25);
    border-radius: 18px;
    padding: 18px 16px 22px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.6);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.kemetic-stat-card:hover {
    border-color: #F2C94C;
    transform: translateY(-4px);
    box-shadow: 0 18px 45px rgba(242, 201, 76, 0.15);
}

/* Header */
.kemetic-stat-header {
    margin-bottom: 14px;
}

.kemetic-stat-title {
    font-size: 16px;
    font-weight: 700;
    color: #F2C94C;
    letter-spacing: 0.4px;
}

/* Chart */
.kemetic-chart-wrapper {
    background: radial-gradient(circle at center, #1f1f1f, #0f0f0f);
    border-radius: 14px;
    padding: 12px;
    border: 1px solid rgba(255, 255, 255, 0.06);
}

/* Legends */
.kemetic-chart-legends {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    box-shadow: 0 0 8px currentColor;
}

/* Colors */
.legend-dot.primary {
    background: #F2C94C;
    color: #F2C94C;
}

.legend-dot.secondary {
    background: #9B8C4A;
    color: #9B8C4A;
}

.legend-dot.warning {
    background: #E5A100;
    color: #E5A100;
}

.legend-text {
    font-size: 14px;
    font-weight: 500;
    color: #cfcfcf;
}

/* Responsive */
@media (max-width: 768px) {
    .kemetic-stat-card {
        padding: 16px;
    }

    .kemetic-stat-title {
        font-size: 15px;
    }
}

</style>

<div class="col-12 col-md-3 mt-20">
    <div class="kemetic-stat-card">

        <!-- Title -->
        <div class="kemetic-stat-header">
            <span class="kemetic-stat-title">
                {{ $cardTitle }}
            </span>
        </div>

        <!-- Chart -->
        <div class="kemetic-chart-wrapper">
            <canvas id="{{ $cardId }}" height="190"></canvas>
        </div>

        <!-- Legends -->
        <div class="kemetic-chart-legends">
            <div class="legend-item">
                <span class="legend-dot primary"></span>
                <span class="legend-text">{{ $cardPrimaryLabel }}</span>
            </div>

            <div class="legend-item">
                <span class="legend-dot secondary"></span>
                <span class="legend-text">{{ $cardSecondaryLabel }}</span>
            </div>

            <div class="legend-item">
                <span class="legend-dot warning"></span>
                <span class="legend-text">{{ $cardWarningLabel }}</span>
            </div>
        </div>

    </div>
</div>
