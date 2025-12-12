import { Link } from "react-router";
import { Calendar, Users, MapPin } from "lucide-react";

/**
 * A component that renders a grid of challenge cards.
 *
 * This component iterates through a list of filtered challenges and displays them
 * as clickable cards linking to their respective detail pages. Each card displays
 * key information such as status, difficulty, category, location, dates, and participant count.
 * @returns {JSX.Element} A grid layout containing challenge cards.
 */
function ChallengeListCard({
  filteredChallenges,
  getStatusBadge,
  getDifficultyColor,
}) {
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {/* Iterate over challenges to create individual cards */}
      {filteredChallenges.map((challenge) => (
        <Link
          key={challenge.id}
          to={`/challenges/${challenge.id}`}
          className="bg-white rounded-xl shadow-sm border border-neutral-gray/20 hover:shadow-lg hover:shadow-primary/10 transition-all overflow-hidden group"
        >
          {/* Card Header: Image/Gradient background with badges */}
          <div className="h-48 bg-gradient-to-br from-secondary to-tertiary relative overflow-hidden">
            <div className="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-colors" />

            {/* Top badges: Status and Difficulty */}
            <div className="absolute top-4 left-4 right-4 flex justify-between items-start">
              {getStatusBadge(challenge.status)}
              <span
                className={`px-3 py-1 rounded-full text-sm font-medium ${getDifficultyColor(
                  challenge.difficulty
                )}`}
              >
                {challenge.difficulty}
              </span>
            </div>

            {/* Bottom badges: Category and Location */}
            <div className="absolute bottom-4 left-4 right-4 flex justify-between items-end">
              <span className="px-3 py-1 bg-white/90 text-primary rounded-full text-sm font-medium shadow-sm">
                {challenge.category}
              </span>
              <span className="px-3 py-1 bg-black/50 text-white rounded-full text-sm flex items-center gap-1 backdrop-blur-sm font-medium">
                <MapPin className="w-3 h-3" />
                {challenge.city}
              </span>
            </div>
          </div>

          {/* Card Body: Title, Description, and Details */}
          <div className="p-6">
            <h3 className="text-xl text-dark-text font-bold mb-2 group-hover:text-primary transition-colors">
              {challenge.title}
            </h3>
            <p className="text-neutral-gray mb-4 line-clamp-2">
              {challenge.description}
            </p>

            {/* Info Section: Dates and Participants */}
            <div className="space-y-2 text-sm text-neutral-gray">
              <div className="flex items-center gap-2">
                <Calendar className="w-4 h-4 text-primary" />
                <span>
                  Commence le :{" "}
                  {new Date(challenge.startDate).toLocaleDateString("fr-FR")} -
                  Fin le :{" "}
                  {new Date(challenge.endDate).toLocaleDateString("fr-FR")}
                </span>
              </div>
              <div className="flex items-center gap-2">
                <Users className="w-4 h-4 text-primary" />
                <span>{challenge.participants} participants</span>
              </div>
            </div>

            {/* Card Footer: Creator and Action Button */}
            <div className="mt-4 pt-4 border-t border-neutral-gray/20 flex items-center justify-between">
              <span className="text-sm font-bold text-dark-text">
                par {challenge.creator}
              </span>
              <button className="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors text-sm font-medium shadow-md shadow-primary/20">
                Rejoindre
              </button>
            </div>
          </div>
        </Link>
      ))}
    </div>
  );
}

export default ChallengeListCard;
