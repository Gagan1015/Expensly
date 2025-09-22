// Dashboard Charts JavaScript
document.addEventListener("DOMContentLoaded", function() {
  // Check if Chart.js is loaded
  if (typeof Chart === 'undefined') {
    console.error('Chart.js is not loaded. Please ensure Chart.js is included before this script.');
    return;
  }

  // Chart.js default configuration
  Chart.defaults.font.family = "Inter, system-ui, sans-serif";
  Chart.defaults.color = "#64748b";
  Chart.defaults.borderColor = "rgba(226, 232, 240, 0.3)";
  Chart.defaults.backgroundColor = "rgba(8, 145, 178, 0.1)";

  // Get chart data from the page
  const chartDataElement = document.getElementById('chartData');
  const categoryDataElement = document.getElementById('categoryData');
  
  let chartData = [];
  let categoryData = [];
  
  try {
    if (chartDataElement && chartDataElement.textContent.trim()) {
      chartData = JSON.parse(chartDataElement.textContent);
    }
    if (categoryDataElement && categoryDataElement.textContent.trim()) {
      categoryData = JSON.parse(categoryDataElement.textContent);
    }
  } catch (e) {
    console.error('JSON Parse Error:', e);
    console.error('Chart data content:', chartDataElement ? chartDataElement.textContent : 'not found');
    console.error('Category data content:', categoryDataElement ? categoryDataElement.textContent : 'not found');
  }

  // Expense Chart (Line Chart)
  const expenseCtx = document.getElementById("expenseChart");
  if (expenseCtx) {
    const labels = chartData.length > 0 ? chartData.map(item => item.date) : ["Aug", "Sep", "Oct", "Nov", "Dec", "Jan"];
    const data = chartData.length > 0 ? chartData.map(item => parseFloat(item.amount)) : [32000, 28000, 35000, 42000, 38000, 45000];
    
    new Chart(expenseCtx, {
      type: "line",
      data: {
        labels: labels,
        datasets: [{
          label: "Daily Expenses",
          data: data,
          borderColor: "#0891b2",
          backgroundColor: "rgba(8, 145, 178, 0.1)",
          borderWidth: 3,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: "#0891b2",
          pointBorderColor: "#ffffff",
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: "rgba(30, 41, 59, 0.9)",
            titleColor: "#f1f5f9",
            bodyColor: "#f1f5f9",
            borderColor: "#0891b2",
            borderWidth: 1,
            cornerRadius: 8,
            callbacks: {
              label: function(context) {
                return "₹" + context.parsed.y.toLocaleString();
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            border: {
              display: false
            }
          },
          y: {
            grid: {
              color: "rgba(226, 232, 240, 0.3)"
            },
            border: {
              display: false
            },
            ticks: {
              callback: function(value) {
                return "₹" + (value >= 1000 ? (value / 1000) + "k" : value);
              }
            }
          }
        },
        interaction: {
          intersect: false,
          mode: "index"
        }
      }
    });
  }

  // Category Chart (Doughnut Chart)
  const categoryCtx = document.getElementById("categoryChart");
  if (categoryCtx) {
    const categoryLabels = categoryData.length > 0 ? categoryData.map(item => item.name) : ["Food", "Transport", "Entertainment", "Health", "Shopping"];
    const categoryAmounts = categoryData.length > 0 ? categoryData.map(item => parseFloat(item.total)) : [18000, 9000, 6750, 4500, 6750];
    const categoryColors = categoryData.length > 0 ? categoryData.map(item => item.color) : ["#3b82f6", "#f59e0b", "#10b981", "#ef4444", "#8b5cf6"];
    
    new Chart(categoryCtx, {
      type: "doughnut",
      data: {
        labels: categoryLabels,
        datasets: [{
          data: categoryAmounts,
          backgroundColor: categoryColors,
          borderWidth: 0,
          hoverBorderWidth: 3,
          hoverBorderColor: "#ffffff"
        }]
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
                weight: "500"
              }
            }
          },
          tooltip: {
            backgroundColor: "rgba(30, 41, 59, 0.9)",
            titleColor: "#f1f5f9",
            bodyColor: "#f1f5f9",
            borderColor: "#0891b2",
            borderWidth: 1,
            cornerRadius: 8,
            callbacks: {
              label: function(context) {
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = ((context.parsed * 100) / total).toFixed(1);
                return context.label + ": ₹" + context.parsed.toLocaleString() + " (" + percentage + "%)";
              }
            }
          }
        }
      }
    });
  }

  // Budget form submission
  const budgetForm = document.getElementById('budgetForm');
  if (budgetForm) {
    budgetForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      
      fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Close modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('budgetModal'));
          if (modal) modal.hide();
          
          // Update budget display
          location.reload(); // Simple reload for now
          
          // Show success message
          if (typeof showToast === 'function') {
            showToast(data.message, 'success');
          } else {
            alert(data.message);
          }
        } else {
          if (typeof showToast === 'function') {
            showToast(data.message || 'Error updating budget', 'error');
          } else {
            alert(data.message || 'Error updating budget');
          }
        }
      })
      .catch(error => {
        console.error('Error:', error);
        if (typeof showToast === 'function') {
          showToast('Error updating budget', 'error');
        } else {
          alert('Error updating budget');
        }
      });
    });
  }

  // Add smooth animations to stat cards
  const statCards = document.querySelectorAll(".stat-card");
  statCards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
    card.classList.add("animate-fade-in-up");
  });

  // Add hover effects to transaction items
  const transactionItems = document.querySelectorAll(".transaction-item");
  transactionItems.forEach((item) => {
    item.addEventListener("mouseenter", function() {
      this.style.transform = "translateX(8px)";
    });

    item.addEventListener("mouseleave", function() {
      this.style.transform = "translateX(0)";
    });
  });
});

// Add CSS animation class
const style = document.createElement("style");
style.textContent = `
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
`;
document.head.appendChild(style);