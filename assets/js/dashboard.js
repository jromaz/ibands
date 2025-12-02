/* =========================================================
   BandS - Dashboard / Roles dinámicos (sin reload)
   ========================================================= */

(() => {
  "use strict";

  const root = document.getElementById("bands-app-root");
  if (!root) return; // Estamos en otra página, salir.

  const qs  = (sel, ctx = document) => (ctx || document).querySelector(sel);

  const loadView = async (viewName) => {
    try {
      const res = await fetch(`views/${viewName}.php`, { cache: "no-store" });
      if (!res.ok) {
        throw new Error(`No se pudo cargar vista: ${viewName}`);
      }
      const html = await res.text();
      root.innerHTML = html;

      if (viewName === "dashboard_inversor") {
        initInvestorDashboard();
      } else if (viewName === "dashboard_admin") {
        initAdminDashboard();
      } else if (viewName === "dashboard_visitante") {
        initVisitorView();
      }
    } catch (err) {
      console.error(err);
      root.innerHTML = `
        <div class="alert alert-danger m-3">
          Ocurrió un error al cargar la vista. Intente nuevamente.
        </div>
      `;
    }
  };

  const resolveViewFromRole = (role) => {
    switch ((role || "").toLowerCase()) {
      case "inversor":
        return "dashboard_inversor";
      case "admin_propiedades":
        return "dashboard_admin";
      default:
        return "dashboard_visitante";
    }
  };

  const bootstrap = async () => {
    try {
      const res = await fetch("api/user_profile.php", { cache: "no-store" });
      if (!res.ok) {
        throw new Error("No se pudo obtener el perfil de usuario");
      }
      const profile = await res.json();
      const view = resolveViewFromRole(profile.role);
      await loadView(view);
    } catch (err) {
      console.error(err);
      await loadView("dashboard_visitante");
    }
  };

  /* -------------------------------------------------------
     LÓGICA ESPECÍFICA: INVERsOR
     ------------------------------------------------------- */

  const initInvestorDashboard = async () => {
    try {
      const res = await fetch("api/investor_positions.php", { cache: "no-store" });
      if (!res.ok) throw new Error("Error al cargar posiciones");
      const data = await res.json();
      const positions = data.positions || [];

      const total = positions.reduce(
        (sum, p) => sum + Number(p.amount_invested || 0),
        0
      );
      const count = positions.length;

      const summaryEl = document.getElementById("investorSummary");
      if (summaryEl) {
        summaryEl.innerHTML = `
          <div class="col-6 col-md-3">
            <div class="bands-card p-2 text-center">
              <div class="small text-muted">Total invertido</div>
              <div class="fw-bold">USD ${total.toLocaleString()}</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="bands-card p-2 text-center">
              <div class="small text-muted">Proyectos</div>
              <div class="fw-bold">${count}</div>
            </div>
          </div>
        `;
      }

      // Gráfico ROI por proyecto
      if (typeof Chart !== "undefined") {
        const ctx = document.getElementById("investorRoiChart");
        if (ctx) {
          const labels = positions.map(p => p.title);
          const roiData = positions.map(p => Number(p.roi_percent || 0));

          new Chart(ctx, {
            type: "bar",
            data: {
              labels,
              datasets: [{
                label: "ROI (%)",
                data: roiData
              }]
            },
            options: {
              plugins: {
                legend: { display: false }
              },
              scales: {
                y: { beginAtZero: true }
              }
            }
          });
        }
      }

      // Tabla detalle
      const tableBody = qs("#investorPositionsTable tbody");
      if (tableBody) {
        tableBody.innerHTML = positions.map(p => {
          const avance = Number(p.progress_percent || 0);
          return `
            <tr>
              <td>
                <div class="small fw-semibold">${escapeHtml(p.title || "")}</div>
                <div class="small text-muted">${escapeHtml(p.location_name || "")}</div>
              </td>
              <td class="text-end small">USD ${Number(p.amount_invested || 0).toLocaleString()}</td>
              <td class="text-end small">${Number(p.roi_percent || 0).toFixed(2)}%</td>
              <td class="text-center small">${escapeHtml(p.status || "")}</td>
              <td class="text-center small">${avance}%</td>
            </tr>
          `;
        }).join("");
      }

    } catch (err) {
      console.error(err);
      const summaryEl = document.getElementById("investorSummary");
      if (summaryEl) {
        summaryEl.innerHTML = `
          <div class="col-12">
            <div class="alert alert-warning small mb-0">
              No se pudo cargar el resumen de inversiones. Intente nuevamente.
            </div>
          </div>
        `;
      }
    }
  };

  /* -------------------------------------------------------
     LÓGICA ESPECÍFICA: ADMIN
     (dejamos un esqueleto para que lo completes)
     ------------------------------------------------------- */

  const initAdminDashboard = () => {
    // Podés agregar aquí la lógica para:
    // - Leer estadísticas rápidas (api/admin_properties.php)
    // - Manejar envío del formulario simple (adminSimpleForm)
    // Por ahora solo dejamos la estructura limpia para no romper nada.
    console.log("Dashboard admin inicializado");
  };

  /* -------------------------------------------------------
     LÓGICA ESPECÍFICA: VISITANTE
     ------------------------------------------------------- */

  const initVisitorView = () => {
    // Vista simple, por ahora no necesita lógica adicional.
    console.log("Dashboard visitante inicializado");
  };

  /* -------------------------------------------------------
     HELPERS
     ------------------------------------------------------- */

  const escapeHtml = (str) => {
    if (!str) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;");
  };

  document.addEventListener("DOMContentLoaded", bootstrap);
})();
