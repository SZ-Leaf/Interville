import { useState } from "react";
import { useNavigate } from "react-router";
import { ArrowLeft, Calendar, Target, Users, AlertCircle } from "lucide-react";
import * as challengeService from "@/service/challengeService";

const THEMES = [
  "Santé & Bien-être",
  "Développement personnel",
  "Pleine conscience",
  "Technologie",
  "Environnement",
  "Créativité",
  "Finance",
  "Social",
];

function CreateChallenge() {
  const navigate = useNavigate();
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [error, setError] = useState("");
  const [formData, setFormData] = useState({
    title: "",
    theme: "",
    description: "",
    fullDescription: "",
    difficulty: "Medium",
    startDate: "",
    endDate: "",
    rules: ["", "", ""],
  });

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");

    // Validation
    if (!formData.title || !formData.theme || !formData.description) {
      setError("Veuillez remplir tous les champs obligatoires");
      return;
    }

    if (new Date(formData.endDate) <= new Date(formData.startDate)) {
      setError("La date de fin doit être postérieure à la date de début");
      return;
    }

    setIsSubmitting(true);

    try {
      // Filter out empty rules
      const filteredRules = formData.rules.filter((rule) => rule.trim() !== "");

      const challengeData = {
        title: formData.title,
        category: formData.theme,
        description: formData.description,
        fullDescription: formData.fullDescription,
        difficulty: formData.difficulty,
        startDate: formData.startDate,
        endDate: formData.endDate,
        rules: filteredRules,
      };

      const createdChallenge = await challengeService.createChallenge(
        challengeData
      );

      // Redirect to the new challenge detail page
      navigate(`/challenges/${createdChallenge.id}`);
    } catch (err) {
      setError(
        err.message || "Échec de la création du défi. Veuillez réessayer."
      );
      console.error("Error creating challenge:", err);
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleRuleChange = (index, value) => {
    const newRules = [...formData.rules];
    newRules[index] = value;
    setFormData({ ...formData, rules: newRules });
  };

  const addRule = () => {
    setFormData({ ...formData, rules: [...formData.rules, ""] });
  };

  return (
    <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <button
        onClick={() => navigate("/challenges")}
        className="inline-flex items-center gap-2 text-primary hover:opacity-80 mb-6"
      >
        <ArrowLeft className="w-4 h-4" />
        Retour aux défis
      </button>

      <div className="bg-card rounded-xl shadow-sm border border-border p-8">
        <div className="mb-8">
          <h1 className="text-3xl text-foreground mb-2">
            Créer un nouveau défi
          </h1>
          <p className="text-muted-foreground">
            Concevez un défi et inspirez les autres à vous rejoindre
          </p>
        </div>

        {error && (
          <div className="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
            <AlertCircle className="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" />
            <p className="text-sm text-red-800">{error}</p>
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Basic Info */}
          <div>
            <h2 className="text-xl text-foreground mb-4 flex items-center gap-2">
              <Target className="w-5 h-5" />
              Informations de base
            </h2>

            <div className="space-y-4">
              <div>
                <label className="block text-foreground mb-2">
                  Titre du défi *
                </label>
                <input
                  type="text"
                  value={formData.title}
                  onChange={(e) =>
                    setFormData({ ...formData, title: e.target.value })
                  }
                  placeholder="ex: Défi fitness de 30 jours"
                  className="w-full px-4 py-3 border border-border rounded-lg bg-input-background focus:outline-none focus:ring-2 focus:ring-ring"
                  required
                  disabled={isSubmitting}
                />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-foreground mb-2">Thème *</label>
                  <select
                    value={formData.theme}
                    onChange={(e) =>
                      setFormData({ ...formData, theme: e.target.value })
                    }
                    className="w-full px-4 py-3 border border-border rounded-lg bg-input-background focus:outline-none focus:ring-2 focus:ring-ring"
                    required
                    disabled={isSubmitting}
                  >
                    <option value="">Sélectionner un thème</option>
                    {THEMES.map((theme) => (
                      <option key={theme} value={theme}>
                        {theme}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-foreground mb-2">
                    Difficulté *
                  </label>
                  <select
                    value={formData.difficulty}
                    onChange={(e) =>
                      setFormData({ ...formData, difficulty: e.target.value })
                    }
                    className="w-full px-4 py-3 border border-border rounded-lg bg-input-background focus:outline-none focus:ring-2 focus:ring-ring"
                    required
                    disabled={isSubmitting}
                  >
                    <option value="Easy">Facile</option>
                    <option value="Medium">Moyen</option>
                    <option value="Hard">Difficile</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-foreground mb-2">
                  Description courte *
                </label>
                <input
                  type="text"
                  value={formData.description}
                  onChange={(e) =>
                    setFormData({ ...formData, description: e.target.value })
                  }
                  placeholder="Brève description (une phrase)"
                  className="w-full px-4 py-3 border border-border rounded-lg bg-input-background focus:outline-none focus:ring-2 focus:ring-ring"
                  required
                  disabled={isSubmitting}
                />
              </div>

              <div>
                <label className="block text-foreground mb-2">
                  Description complète *
                </label>
                <textarea
                  value={formData.fullDescription}
                  onChange={(e) =>
                    setFormData({
                      ...formData,
                      fullDescription: e.target.value,
                    })
                  }
                  placeholder="Fournissez des informations détaillées sur votre défi..."
                  className="w-full px-4 py-3 border border-border rounded-lg bg-input-background focus:outline-none focus:ring-2 focus:ring-ring resize-none"
                  rows={4}
                  required
                  disabled={isSubmitting}
                />
              </div>
            </div>
          </div>

          {/* Timeline */}
          <div>
            <h2 className="text-xl text-foreground mb-4 flex items-center gap-2">
              <Calendar className="w-5 h-5" />
              Calendrier
            </h2>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="block text-foreground mb-2">
                  Date de début *
                </label>
                <input
                  type="date"
                  value={formData.startDate}
                  onChange={(e) =>
                    setFormData({ ...formData, startDate: e.target.value })
                  }
                  className="w-full px-4 py-3 border border-border rounded-lg bg-input-background focus:outline-none focus:ring-2 focus:ring-ring"
                  required
                  disabled={isSubmitting}
                />
              </div>

              <div>
                <label className="block text-foreground mb-2">
                  Date de fin *
                </label>
                <input
                  type="date"
                  value={formData.endDate}
                  onChange={(e) =>
                    setFormData({ ...formData, endDate: e.target.value })
                  }
                  className="w-full px-4 py-3 border border-border rounded-lg bg-input-background focus:outline-none focus:ring-2 focus:ring-ring"
                  required
                  disabled={isSubmitting}
                />
              </div>
            </div>
          </div>

          {/* Rules */}
          <div>
            <h2 className="text-xl text-foreground mb-4 flex items-center gap-2">
              <Users className="w-5 h-5" />
              Règles du défi
            </h2>

            <div className="space-y-3">
              {formData.rules.map((rule, index) => (
                <div key={index}>
                  <label className="block text-foreground mb-2">
                    Règle {index + 1}
                  </label>
                  <input
                    type="text"
                    value={rule}
                    onChange={(e) => handleRuleChange(index, e.target.value)}
                    placeholder="Entrez une règle du défi"
                    className="w-full px-4 py-3 border border-border rounded-lg bg-input-background focus:outline-none focus:ring-2 focus:ring-ring"
                    disabled={isSubmitting}
                  />
                </div>
              ))}
              <button
                type="button"
                onClick={addRule}
                className="text-primary hover:opacity-80"
                disabled={isSubmitting}
              >
                + Ajouter une autre règle
              </button>
            </div>
          </div>

          {/* Submit */}
          <div className="flex gap-4 pt-6 border-t border-border">
            <button
              type="button"
              onClick={() => navigate("/challenges")}
              className="flex-1 px-6 py-3 border border-border rounded-lg hover:bg-muted transition-colors"
              disabled={isSubmitting}
            >
              Annuler
            </button>
            <button
              type="submit"
              className="flex-1 px-6 py-3 bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity disabled:opacity-50"
              disabled={isSubmitting}
            >
              {isSubmitting ? "Création..." : "Créer le défi"}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

export default CreateChallenge;
