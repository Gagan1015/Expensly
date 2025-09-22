import { Chart } from "@/components/ui/chart"
// Dashboard Charts JavaScript
document.addEventListener("DOMContentLoaded", () => {
  // Chart.js default configuration
  Chart.defaults.font.family = "Inter, system-ui, sans-serif"
  Chart.defaults.color = "#64748b"
  Chart.defaults.borderColor = "rgba(226, 232, 240, 0.3)"
  Chart.defaults.backgroundColor = "rgba(8, 145, 178, 0.1)"

  // Expense Chart (Line Chart)
  const expenseCtx = document.getElementById("expenseChart")
  if (expenseCtx) {
    new Chart(expenseCtx, {
      type: "line",
      data: {
        labels: ["Aug", "Sep", "Oct", "Nov", "Dec", "Jan"],
        datasets: [
          {
            label: "Monthly Expenses",
            data: [32000, 28000, 35000, 42000, 38000, 45000],
            borderColor: "#0891b2",
            backgroundColor: "rgba(8, 145, 178, 0.1)",
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: "#0891b2",
            pointBorderColor: "#ffffff",
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            backgroundColor: "rgba(30, 41, 59, 0.9)",
            titleColor: "#f1f5f9",
            bodyColor: "#f1f5f9",
            borderColor: "#0891b2",
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: false,
            callbacks: {
              label: (context) => "₹" + context.parsed.y.toLocaleString(),
            },
          },
        },
        scales: {
          x: {
            grid: {
              display: false,
            },
            border: {
              display: false,
            },
          },
          y: {
            grid: {
              color: "rgba(226, 232, 240, 0.3)",
            },
            border: {
              display: false,
            },
            ticks: {
              callback: (value) => "₹" + value / 1000 + "k",
            },
          },
        },
        interaction: {
          intersect: false,
          mode: "index",
        },
      },
    })
  }

  // Category Chart (Doughnut Chart)
  const categoryCtx = document.getElementById("categoryChart")
  if (categoryCtx) {
    new Chart(categoryCtx, {
      type: "doughnut",
      data: {
        labels: ["Food", "Transport", "Entertainment", "Health", "Shopping"],
        datasets: [
          {
            data: [18000, 9000, 6750, 4500, 6750],
            backgroundColor: ["#3b82f6", "#f59e0b", "#10b981", "#ef4444", "#8b5cf6"],
            borderWidth: 0,
            hoverBorderWidth: 3,
            hoverBorderColor: "#ffffff",
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "60%",
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              padding: 20,
              usePointStyle: true,
              pointStyle: "circle",
              font: {
                size: 12,
                weight: "500",
              },
            },
          },
          tooltip: {
            backgroundColor: "rgba(30, 41, 59, 0.9)",
            titleColor: "#f1f5f9",
            bodyColor: "#f1f5f9",
            borderColor: "#0891b2",
            borderWidth: 1,
            cornerRadius: 8,
            callbacks: {
              label: (context) => {
                const total = context.dataset.data.reduce((a, b) => a + b, 0)
                const percentage = ((context.parsed * 100) / total).toFixed(1)
                return context.label + ": ₹" + context.parsed.toLocaleString() + " (" + percentage + "%)"
              },
            },
          },
        },
      },
    })
  }

  // Add smooth animations to stat cards
  const statCards = document.querySelectorAll(".stat-card")
  statCards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`
    card.classList.add("animate-fade-in-up")
  })

  // Add hover effects to transaction items
  const transactionItems = document.querySelectorAll(".transaction-item")
  transactionItems.forEach((item) => {
    item.addEventListener("mouseenter", function () {
      this.style.transform = "translateX(8px)"
    })

    item.addEventListener("mouseleave", function () {
      this.style.transform = "translateX(0)"
    })
  })
})

// Add CSS animation class
const style = document.createElement("style")
style.textContent = `
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
`
document.head.appendChild(style)
